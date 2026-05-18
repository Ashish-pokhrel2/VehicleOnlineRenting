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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'payment' => [
        'verify_ssl' => env('PAYMENT_VERIFY_SSL', true),
    ],

    'khalti' => [
        'base_url' => env('KHALTI_BASE_URL', 'https://dev.khalti.com/api/v2'),
        'secret_key' => env('KHALTI_SECRET_KEY'),
    ],

    'esewa' => [
        'epay_url' => env('ESEWA_EPAY_URL', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form'),
        'status_url' => env('ESEWA_STATUS_URL', 'https://rc.esewa.com.np/api/epay/transaction/status'),
        'product_code' => env('ESEWA_PRODUCT_CODE', 'EPAYTEST'),
        'secret_key' => env('ESEWA_SECRET_KEY', env('ESEWA_ACCESS_KEY')),
    ],

];