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

    'pubnub' => [
        'pub_key' => env('PUBNUB_PUBKEY', ''),
        'sub_key' => env('PUBNUB_SUBKEY', ''),
    ],

	'rollbar' => [
		'token' => env('ROLLBAR_TOKEN', ''),
	],

];
