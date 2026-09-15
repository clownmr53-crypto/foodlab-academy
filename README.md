# FoodLab Academy (MVP)

LMS francophone pour métiers de bouche — Laravel + Blade, SQLite en local.

## Prérequis

- PHP 8.3+ (testé 8.4) avec extensions sqlite, gd, mbstring, xml, zip
- Composer
- Node.js 18+ (pour Vite / assets Tailwind)
- SQLite

## Installation locale

```bash
cd foodlab-academy
composer install
cp .env.example .env   # si besoin
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

Ouvrir http://127.0.0.1:8000

## Comptes de démonstration (mot de passe : `password`)

| E-mail | Rôle / plan |
|--------|-------------|
| admin@foodlab.test | Admin + Premium |
| starter@foodlab.test | Élève Starter |
| premium@foodlab.test | Élève Premium |
| free@foodlab.test | Sans abonnement |

## Variables d'environnement importantes

Voir `.env.example` :

- **Plans** : `FOODLAB_STARTER_PRICE`, `FOODLAB_PREMIUM_PRICE`, `FOODLAB_CURRENCY`
- **Paiements** : Stripe (`STRIPE_*`), Mobile Money (`FOODLAB_MM_PROVIDER=kkiapay` ou `fedapay`, clés KKiaPay / FedaPay)
- **Vidéo** : `FOODLAB_VIDEO_PROVIDER=bunny` (ou `vimeo`), `BUNNY_LIBRARY_ID`, `VIMEO_ACCESS_TOKEN`
- En local, le checkout propose une **simulation de paiement** (sandbox) si les clés ne sont pas renseignées.

Webhooks :

- Stripe : `POST /webhooks/stripe`
- Mobile Money : `POST /webhooks/mobile-money`

## Fonctionnalités MVP

- Auth (inscription, vérification e-mail, reset, profil pays/secteur/niveau)
- Plans Starter / Premium, upgrade, reçus e-mail
- LMS 6 modules (4–6 verrouillés Starter), progression, embeds vidéo
- Calculateur coût de revient + PDF + Excel
- Certificat Premium PDF + QR, vérification publique `/verify-certificate`
- Admin : modules/leçons, témoignages, FAQ, pages légales, messages contact
- CGV / mentions / confidentialité + bannière cookies

## Tests

```bash
php artisan test
```

## Déploiement Render

Déploiement Docker prêt pour [Render](https://render.com) (web service + Postgres).

### Fichiers fournis

- `Dockerfile` — PHP 8.3, extensions Laravel, `composer install --no-dev`, build Vite, écoute sur `$PORT`
- `docker/entrypoint.sh` — cache config/routes, `migrate --force`, seed optionnel, `php artisan serve`
- `.dockerignore`
- `render.yaml` — Blueprint (web + Postgres `basic_256mb`)

### Clé d'application

Générer une clé Laravel (ne pas committer) :

```bash
php artisan key:generate --show
```

Coller la valeur dans Render → Environment → `APP_KEY` (format `base64:...`).

### Variables Render essentielles

| Variable | Valeur |
|----------|--------|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://<votre-service>.onrender.com` |
| `APP_KEY` | sortie de `key:generate --show` |
| `DB_CONNECTION` | `pgsql` |
| `DATABASE_URL` | fourni par le Postgres Render (ou Neon/Supabase) |
| `SEED_ON_DEPLOY` | `true` une fois pour peupler la démo, puis `false` |
| `APP_LOCALE` / `APP_FALLBACK_LOCALE` | `fr` |
| `AUTO_VERIFY_EMAIL` | `true` (MVP sans SMTP) — mettre `false` + vrai SMTP pour la prod officielle |
| `MAIL_MAILER` | `log` sur Render MVP ; `smtp` + Brevo/SendGrid en officiel |


SQLite reste supporté en local (`DB_CONNECTION=sqlite`). En production, utiliser **pgsql** via `DATABASE_URL`.

### Étapes

1. Pousser le dépôt Git et créer un Blueprint depuis `render.yaml`, **ou** New → Web Service → Docker.
2. Attacher une base **PostgreSQL** (le plan free Postgres Render n'existe plus ; `basic_256mb` dans le Blueprint, ou Postgres externe gratuit).
3. Renseigner `APP_KEY`, `APP_URL`, et éventuellement Stripe / Mobile Money / Bunny.
4. Premier déploiement : mettre `SEED_ON_DEPLOY=true` pour les comptes démo, puis repasser à `false`.
5. Configurer les webhooks Stripe / KKiaPay / FedaPay vers `https://<host>/webhooks/...`.


### E-mail & vérification (MVP Render)

Render n'offre pas de SMTP réel par défaut. Pour le MVP / démo :

- À l'inscription, l'e-mail est **auto-vérifié** lorsque `AUTO_VERIFY_EMAIL=true` **ou** lorsque `MAIL_MAILER` vaut `log` / `array` (et SMTP non configuré).
- Les routes / UI de vérification et de reset mot de passe restent en place.
- Les messages de validation Laravel sont en **français** (`lang/fr`).

### Google OAuth (Socialite)

Boutons « Continuer / S'inscrire avec Google » sur login et register.

1. Google Cloud Console → APIs & Services → Credentials → OAuth 2.0 Client ID (Web)
2. Authorized redirect URI : `https://<votre-host>/auth/google/callback`
3. Sur Render, définir :

| Variable | Exemple |
|----------|---------|
| `GOOGLE_CLIENT_ID` | (client id) |
| `GOOGLE_CLIENT_SECRET` | (secret) |
| `GOOGLE_REDIRECT_URI` | `https://<votre-host>/auth/google/callback` |

Sans ces variables, le bouton renvoie une erreur claire (pas de crash).

### E-mail SMTP / Brevo (prod officielle)

| Variable | Exemple Brevo |
|----------|----------------|
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp-relay.brevo.com` |
| `MAIL_PORT` | `587` |
| `MAIL_ENCRYPTION` | `tls` (ou `MAIL_SCHEME=smtp`) |
| `MAIL_USERNAME` | e-mail compte Brevo |
| `MAIL_PASSWORD` | clé SMTP Brevo |
| `MAIL_FROM_ADDRESS` | `noreply@votre-domaine.com` |
| `MAIL_FROM_NAME` | `FoodLab Academy` |
| `AUTO_VERIFY_EMAIL` | `false` |

Quand un vrai SMTP est configuré (`MAIL_MAILER=smtp` + host/username), les nouveaux comptes **ne sont plus auto-vérifiés** : Laravel envoie l'e-mail de vérification. Sans SMTP (`MAIL_MAILER=log`), `AUTO_VERIFY_EMAIL` reste le fallback MVP.


HTTPS : en `APP_ENV=production`, l'app force le schéma HTTPS et fait confiance aux proxies Render (`TrustProxies`).

### Caveats free tier

- `php artisan serve` convient au free tier ; pour plus de charge, passer à nginx + PHP-FPM.
- Le disque Render est éphémère : ne pas compter sur SQLite ni sur les fichiers uploadés en local (`storage`) sans volume / S3.
- Les free web services s'endorment après inactivité.
- Postgres Render exige souvent SSL : ajouter `DB_SSLMODE=require` ou `?sslmode=require` sur `DATABASE_URL`.

## Déploiement (notes générales)

1. Hébergeur PHP 8.3+ (Forge, Ploi, shared hosting compatible Laravel, etc.)
2. Configurer `.env` (APP_URL, base MySQL/Postgres ou SQLite, mail SMTP, clés Stripe/MM/Bunny)
3. `composer install --no-dev -o`
4. `php artisan migrate --force`
5. `npm ci && npm run build` (ou build CI)
6. Document root = `public/`
7. Configurer les URLs de webhooks chez Stripe / KKiaPay / FedaPay
8. `php artisan storage:link` si besoin d'assets publics

## Archive de distribution

Le fichier `foodlab-academy.zip` (à la racine workspace) exclut `vendor/`, `node_modules/` et `.env`.

Après extraction : `composer install`, `cp .env.example .env`, `key:generate`, `migrate --seed`, `npm i && npm run build`.

## Hors scope (V2)

Forum, coaching, Q&A, bibliothèque de templates, Chart.js, comparaison marché, certificat Starter, Meta Pixel/GTM, WhatsApp flottant, load tests.
