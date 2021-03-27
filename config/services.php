<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain'        => env('MAILGUN_DOMAIN'),
        'secret'        => env('MAILGUN_SECRET'),
    ],

    'mailtrap' => [
        'secret'        => env('MAILTRAP_SECRET'),
        'default_inbox' => env('MAILTRAP_INBOX'),
    ],

    'mandrill' => [
        'secret'        => env('MANDRILL_SECRET'),
    ],

    'sparkpost' => [
        'secret'        => env('SPARKPOST_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Raven - used for reporting errors to Sentry
    |--------------------------------------------------------------------------
     *
     * https://github.com/getsentry/sentry
     *
     * DSN is used for backend errors
     * Public DSN is used for frontend errors
     */

    'raven' => [
        'dsn'           => env('RAVEN_DSN'),
        'public_dsn'    => env('RAVEN_PUBLIC_DSN'),
        'level'         => env('RAVEN_LEVEL', 'error'),
    ],

    /** Social services */

    'facebook' => [
        'page_token'      => env('FACEBOOK_PAGE_TOKEN'),
    ],

    'twitter' => [
        'consumer_key'    => env('TWITTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
        'token'           => env('TWITTER_TOKEN'),
        'token_secret'    => env('TWITTER_TOKEN_SECRET'),
    ],

];
