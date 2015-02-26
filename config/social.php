<?php

return [
    'facebook' => [
        'page_token'      => env('FB_PAGE_TOKEN'),
    ],
    'twitter' => [
        'consumer_key'    => env('TWITTER_CK'),
        'consumer_secret' => env('TWITTER_CS'),
        'token'           => env('TWITTER_T'),
        'token_secret'    => env('TWITTER_TS'),
    ],
];
