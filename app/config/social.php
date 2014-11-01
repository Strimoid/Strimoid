<?php

return [
    'facebook' => [
        'page_token'      => $_ENV['fb_page_token'],
    ],
    'twitter' => [
        'consumer_key'    => $_ENV['twitter_ck'],
        'consumer_secret' => $_ENV['twitter_cs'],
        'token'           => $_ENV['twitter_t'],
        'token_secret'    => $_ENV['twitter_ts'],
    ]
];
