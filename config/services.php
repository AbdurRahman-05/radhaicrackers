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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'lionsms' => [
        'api_key' => env('LIONSMS_API_KEY', 'dcd3c5c00112b83116657d7f656660a1'),
        'sender_id' => env('LIONSMS_SENDER_ID', 'RADHTR'),
        'route' => env('LIONSMS_ROUTE', '9'),
        'base_url' => env('LIONSMS_BASE_URL', 'https://msg.lionsms.com/api/smsapi'),
        'otp_template_id' => env('LIONSMS_OTP_TEMPLATE_ID', '1107172187374253331'),
    ],

];
