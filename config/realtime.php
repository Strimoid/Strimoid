<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'pubnub',

    /*
    |--------------------------------------------------------------------------
    | Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [

        'pubnub' => [
            'driver'        => 'pubnub',
            'publish_key'   => env('PUBNUB_PUBLISH_KEY'),
            'subscribe_key' => env('PUBNUB_SUBSCRIBE_KEY'),
            'secret_key'    => env('PUBNUB_SECRET_KEY'),
            'ssl'           => env('PUBNUB_SSL', true),
        ],

        'pusher' => [
            'driver' => 'pusher',
            'app_id' => env('PUSHER_APP_ID'),
            'key'    => env('PUSHER_KEY'),
            'secret' => env('PUSHER_SECRET'),
        ],

        'null' => [
            'driver' => 'null',
        ]

    ],

];
