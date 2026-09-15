<?php

return [
    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'price' => (int) env('FOODLAB_STARTER_PRICE', 25000),
            'currency' => env('FOODLAB_CURRENCY', 'XOF'),
            'modules_access' => [1, 2, 3],
        ],
        'premium' => [
            'name' => 'Premium',
            'price' => (int) env('FOODLAB_PREMIUM_PRICE', 75000),
            'currency' => env('FOODLAB_CURRENCY', 'XOF'),
            'modules_access' => [1, 2, 3, 4, 5, 6],
        ],
    ],

    'payments' => [
        'default_mm_provider' => env('FOODLAB_MM_PROVIDER', 'kkiapay'),
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
            'sandbox' => filter_var(env('FEDAPAY_SANDBOX', true), FILTER_VALIDATE_BOOL),
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
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-verify email on registration (MVP / no SMTP)
    |--------------------------------------------------------------------------
    |
    | When true, new users get email_verified_at set immediately so they are
    | not stuck on the verify-email page. Defaults to true when MAIL_MAILER
    | is log/array (no real SMTP). Set AUTO_VERIFY_EMAIL=false and configure
    | a real mailer (Brevo, SendGrid, SMTP) when going official.
    |
    */
    'auto_verify_email' => env('AUTO_VERIFY_EMAIL') !== null && env('AUTO_VERIFY_EMAIL') !== ''
        ? filter_var(env('AUTO_VERIFY_EMAIL'), FILTER_VALIDATE_BOOL)
        : in_array(env('MAIL_MAILER', 'log'), ['log', 'array'], true),

];
