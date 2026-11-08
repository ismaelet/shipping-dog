<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    ],

    'nexi' => [
        'local_sandbox' => env('NEXI_LOCAL_SANDBOX', false),
        'local_trusted_proxies' => env('TRUSTED_PROXIES'),
        'sandbox' => env('NEXI_SANDBOX', true),
        'external_operations_enabled' => env('NEXI_EXTERNAL_OPERATIONS_ENABLED', false),
        'sandbox_api_key' => env('NEXI_SANDBOX_API_KEY'),
        'api_key' => env('NEXI_API_KEY'),
    ],

    'gls' => [
        'client_id' => env('GLS_CLIENT_ID'),
        'client_password' => env('GLS_CLIENT_PASSWORD'),
        'contract_code' => env('GLS_CONTRACT_CODE'),
        'headquarters' => env('GLS_HEADQUARTERS'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],
];
