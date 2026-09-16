<?php

return [
    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'price' => (int) env('FOODLAB_STARTER_PRICE', 0),
            'currency' => env('FOODLAB_CURRENCY', 'XOF'),
            'currency_label' => 'FCFA',
            'tagline' => '0 FCFA / pour toujours',
            'modules_access' => [1, 2, 3],
            'features' => [
                ['text' => 'Accès à la communauté publique', 'included' => true],
                ['text' => '3 modules d\'introduction', 'included' => true],
                ['text' => 'Ressources découvertes & articles', 'included' => true],
                ['text' => 'Newsletter hebdomadaire', 'included' => true],
                ['text' => 'Calculateur basique', 'included' => true],
                ['text' => 'Modules avancés (4, 5, 6)', 'included' => false],
                ['text' => 'Coaching individuel', 'included' => false],
                ['text' => 'Certificat officiel', 'included' => false],
            ],
        ],
        'premium' => [
            'name' => 'Premium',
            'price' => (int) env('FOODLAB_PREMIUM_PRICE', 149000),
            'currency' => env('FOODLAB_CURRENCY', 'XOF'),
            'currency_label' => 'FCFA',
            'tagline' => 'Programme complet',
            'modules_access' => [1, 2, 3, 4, 5, 6],
            'features' => [
                ['text' => 'Les 6 modules complets', 'included' => true],
                ['text' => 'Calculateur avancé (export PDF/Excel)', 'included' => true],
                ['text' => 'Tous les templates téléchargeables', 'included' => true],
                ['text' => 'Communauté privée exclusive', 'included' => true],
                ['text' => 'Sessions Q&R mensuelles en direct', 'included' => true],
                ['text' => '4 séances de coaching individuel', 'included' => true],
                ['text' => 'Mentorat 1-to-1 (6 mois)', 'included' => true],
                ['text' => 'Certificat officiel + QR code', 'included' => true],
                ['text' => 'Accès à vie aux mises à jour', 'included' => true],
            ],
        ],
    ],

    'payment_methods_labels' => [
        'Orange Money',
        'MTN Money',
        'Moov Money',
        'Wave',
        'Carte bancaire (Stripe)',
    ],

    'guarantee_days' => 14,

    'tastebox' => [
        'price' => (int) env('FOODLAB_TASTEBOX_PRICE', 15000),
        'currency_label' => 'FCFA',
        'guarantee_days' => 7,
    ],


    'payments' => [
        // Prefer MOBILE_MONEY_PROVIDER; fall back to FOODLAB_MM_PROVIDER; default fedapay
        'default_mm_provider' => env('MOBILE_MONEY_PROVIDER', env('FOODLAB_MM_PROVIDER', 'fedapay')),
        'stripe' => [
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'sandbox' => filter_var(env('STRIPE_SANDBOX', true), FILTER_VALIDATE_BOOL),
        ],
        'kkiapay' => [
            'public_key' => env('KKIAPAY_PUBLIC_KEY'),
            'private_key' => env('KKIAPAY_PRIVATE_KEY'),
            'secret' => env('KKIAPAY_SECRET'),
            'sandbox' => filter_var(env('KKIAPAY_SANDBOX', true), FILTER_VALIDATE_BOOL),
        ],
        'fedapay' => [
            'public_key' => env('FEDAPAY_PUBLIC_KEY'),
            'secret_key' => env('FEDAPAY_SECRET_KEY'),
            // FEDAPAY_MODE=sandbox|live preferred; FEDAPAY_SANDBOX kept for backward compat
            'mode' => (static function (): string {
                $mode = env('FEDAPAY_MODE');
                if ($mode !== null && $mode !== '') {
                    return strtolower((string) $mode) === 'live' ? 'live' : 'sandbox';
                }
                $sandbox = filter_var(env('FEDAPAY_SANDBOX', true), FILTER_VALIDATE_BOOL);

                return $sandbox ? 'sandbox' : 'live';
            })(),
            'sandbox' => (static function (): bool {
                $mode = env('FEDAPAY_MODE');
                if ($mode !== null && $mode !== '') {
                    return strtolower((string) $mode) !== 'live';
                }

                return filter_var(env('FEDAPAY_SANDBOX', true), FILTER_VALIDATE_BOOL);
            })(),
            'webhook_secret' => env('FEDAPAY_WEBHOOK_SECRET'),
        ],
    ],

    'video' => [
        'default_provider' => env('FOODLAB_VIDEO_PROVIDER', 'bunny'),
        'bunny' => [
            'library_id' => env('BUNNY_LIBRARY_ID'),
            'cdn_hostname' => env('BUNNY_CDN_HOSTNAME'),
        ],
        'vimeo' => [
            'access_token' => env('VIMEO_ACCESS_TOKEN'),
        ],
    ],

    'certificate' => [
        'issuer' => env('FOODLAB_CERT_ISSUER', 'FoodLab Academy'),
        'title' => 'Certification Premium FoodLab Academy',
        'starter_title' => 'Certificat de participation Starter',
    ],

    'calendly_url' => env('CALENDLY_URL', ''),

    'meta_pixel_id' => env('META_PIXEL_ID', ''),
    'gtm_id' => env('GTM_ID', ''),

    'whatsapp_number' => env('WHATSAPP_NUMBER', ''),
    'whatsapp_url' => env('WHATSAPP_URL', ''),

    /*
    | Explicit AUTO_VERIFY_EMAIL wins. Otherwise: auto-verify only when no real
    | mailer is configured (log/array, or smtp without host/username).
    | When MAIL_MAILER=smtp (+ MAIL_HOST/USERNAME) or another real driver is set,
    | verification emails are sent and users are NOT auto-verified.
    */
    'auto_verify_email' => (static function (): bool {
        $explicit = env('AUTO_VERIFY_EMAIL');
        if ($explicit !== null && $explicit !== '') {
            return filter_var($explicit, FILTER_VALIDATE_BOOL);
        }

        $mailer = env('MAIL_MAILER', 'log');

        if (in_array($mailer, ['log', 'array', null, ''], true)) {
            return true;
        }

        if ($mailer === 'smtp') {
            return ! filled(env('MAIL_HOST')) || ! filled(env('MAIL_USERNAME'));
        }

        return false;
    })(),

];
