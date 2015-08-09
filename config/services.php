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

    'raven' => [
        'dsn'           => env('RAVEN_DSN'),
        'level'         => env('RAVEN_LEVEL', 'error'),
    ],

];
