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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],


    'google' => [
        'client_id' => '819065309496-1vmels0ahruvro89lt7v3cteh61l2njn.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://promotiontalents-cegag6hybkexbgds.uaenorth-01.azurewebsites.net/api/auth/google/callback',
    ],

    'facebook' => [
        'client_id' => '9859642140734179',
        'client_secret' => 'b824c59bae4889e2f1f6bafb83216c59',
        'redirect' => 'https://promotiontalents-cegag6hybkexbgds.uaenorth-01.azurewebsites.net/api/auth/facebook/callback',
    ],


];
