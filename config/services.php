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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sms_ru' => [
        'api_id' => env('SMSRU_API_ID'),
    ],

    'fcm' => [
        'key' => env('FCM_SECRET_KEY', "AAAAB4sPSbI:APA91bFP25mFRqvOyrsm9huAEuzlEXzs8-ALesTJ90X8L3U062eQB5mqGiDjKo3eDi4Ao7RtFlmm9543c5I3hLlUow7ojBg0sW8EzkVlMsN6m3Ujicii4hNLSMiASVEecSO30CMvX1zH")
    ]
];
