<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |

     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value, the allowed methods however have to be explicitly listed.
     |
     */
    'supports_credentials' => false,
    'allowed_origins'      => ['*'],
    'allowed_headers'      => ['*'],
    'allowed_methods'      => ['GET', 'POST', 'PUT',  'DELETE'],
    'exposed_headers'      => [],
    'max_age'              => 0,
    'hosts'                => [],
    'paths'                => ['api/*'],
];
