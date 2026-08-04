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

    'onesignal' => [
        'url' => env('ONE_SIGNAL_URL'),
        'user_url' => env('ONE_SIGNAL_USER_URL'),
        'app_id' => env('ONE_SIGNAL_APP_ID'),
        'rest_api_key' => env('ONE_SIGNAL_REST_API_KEY'),
    ],

    'sms' => [
        'url' => env('SMS_URL'),
        'username' => env('SMS_USERNAME'),
        'password' => env('SMS_PASSWORD'),
        'sender_id' => env('SMS_SENDER_ID'),
        'apikey' => env('SMS_API_KEY'),
        'authorization' => env('SMS_AUTHORIZATION'),
    ],

    'whatsapp' => [
        'url' => env('WHATSAPP_URL'),
    ],

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

    's3bucket' => [
        'status' => env('AWS_BUCKET_STATUS', true),
    ],

];
