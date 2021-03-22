<?php

return [

    'html_snippet' => env('HTML_SNIPPET', ''),
    'homepage' => [
        'threshold'  => 2,
        'time_limit' => 7,
    ],
    'oembed' => [
        'url' => env('OEMBED_URL', 'https://embed.strm.pl'),
        'api_key' => env('OEMBED_API_KEY', null),
    ],
    'meilisearch' => [
        'url' => ENV('MEILISEARCH_URL', 'http://localhost:7700'),
        'master_key' => env('MEILISEARCH_MASTER_KEY'),
    ],

];
