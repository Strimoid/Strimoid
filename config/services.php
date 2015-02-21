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

    'pubnub' => [
        'pub_key'       => env('PUBNUB_PUBKEY'),
        'sub_key'       => env('PUBNUB_SUBKEY'),
        'secret'        => env('PUBNUB_SECRET'),
    ],

	'rollbar' => [
		'access_token'  => env('ROLLBAR_TOKEN'),
        'level'         => env('ROLLBAR_LEVEL', 'debug'),
	],

];
