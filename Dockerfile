# FoodLab Academy — production image for Render
# Simple approach: PHP 8.3 CLI + `php artisan serve` on $PORT (free-tier friendly)

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# gd is provided in the final runtime image; ignore platform ext during vendor build
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --ignore-platform-req=ext-gd
COPY . .
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

FROM php:8.4-cli-bookworm

# Official image already ships curl, mbstring, xml/dom; add DB + media extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libsqlite3-dev \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        pdo_sqlite \
        zip \
        gd \
        bcmath \
        pcntl \
        opcache \
    && php -m | grep -E 'pdo_pgsql|pdo_sqlite|mbstring|xml|curl|zip|gd|bcmath' \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor
COPY . .
COPY --from=assets /app/public/build ./public/build

RUN mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache \
    && chmod +x docker/entrypoint.sh

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    PORT=10000

EXPOSE 10000

ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
