<?php

return [
    'facebook' => [
        'page_token'      => getenv('fb_page_token'),
    ],
    'twitter' => [
        'consumer_key'    => getenv('twitter_ck'),
        'consumer_secret' => getenv('twitter_cs'),
        'token'           => getenv('twitter_t'),
        'token_secret'    => getenv('twitter_ts'),
    ]
];
