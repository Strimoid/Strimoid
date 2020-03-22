<?php

return [

    'homepage' => [
        'threshold'  => 2,
        'time_limit' => 7,
    ],
    'oembed' => [
        'url' => env('OEMBED_URL', 'https://embed.strm.pl'),
        'api_key' => env('OEMBED_API_KEY', null),
    ],

];
