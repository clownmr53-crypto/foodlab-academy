#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

PORT="${PORT:-10000}"

# Ensure writable dirs (Render ephemeral FS)
mkdir -p \
  storage/framework/{cache,sessions,views} \
  storage/logs \
  storage/app/public \
  bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache || true

# Optional: materialize .env from example when missing (Render injects real env vars)
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
  echo "[entrypoint] Created .env from .env.example (override with Render env vars)."
fi

# Fail fast if APP_KEY missing in production
if [ -z "${APP_KEY:-}" ] && ! grep -qE '^APP_KEY=base64:' .env 2>/dev/null; then
  echo "[entrypoint] WARNING: APP_KEY is empty. Set APP_KEY on Render (php artisan key:generate --show)."
fi

# Laravel reads DB_URL; Render/common convention is DATABASE_URL
if [ -n "${DATABASE_URL:-}" ] && [ -z "${DB_URL:-}" ]; then
  export DB_URL="$DATABASE_URL"
fi

php artisan storage:link --force 2>/dev/null || php artisan storage:link || true

php artisan config:cache

# Safe for controller-based routes (this app)
if php artisan route:cache; then
  echo "[entrypoint] Route cache OK."
else
  echo "[entrypoint] route:cache failed; continuing without route cache."
  php artisan route:clear || true
fi

php artisan view:cache || true

php artisan migrate --force

if [ "${SEED_ON_DEPLOY:-false}" = "true" ]; then
  echo "[entrypoint] SEED_ON_DEPLOY=true → running db:seed --force"
  php artisan db:seed --force
fi

echo "[entrypoint] Starting FoodLab Academy on 0.0.0.0:${PORT}"
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
