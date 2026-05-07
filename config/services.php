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

    'exchange_api' => [
        'default' => 'exchange_rates',

        'fixer' => [
            'key' => env('FIXER_API_KEY'),
            'class' => 'Webkul\Core\Helpers\Exchange\FixerExchange',
        ],

        'exchange_rates' => [
            'key' => env('EXCHANGE_RATES_API_KEY'),
            'class' => 'Webkul\Core\Helpers\Exchange\ExchangeRates',
        ],
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_CALLBACK_URL'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_CALLBACK_URL'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_URL'),
    ],

    'linkedin-openid' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_CALLBACK_URL'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_CALLBACK_URL'),
    ],

    /*
    | Crisp chat (storefront). Public website ID from Crisp → Settings → Website → Setup.
    | Agents use https://app.crisp.chat / Crisp mobile apps. Leave empty to hide the widget.
    */
    'crisp' => [
        'website_id' => env('CRISP_WEBSITE_ID'),
    ],

    /*
    | Tawk.to live chat (storefront). Property + widget IDs from Tawk dashboard → Admin → Channels → Chat Widget.
    | Override via .env. Leave empty to hide the widget.
    */
    'tawk' => [
        'property_id' => env('TAWK_PROPERTY_ID', '69dd5769d99cf01c408cdee4'),
        'widget_id' => env('TAWK_WIDGET_ID', '1jm49qt4e'),
    ],
];
