<?php

return [

    'name' => env('API_NAME', 'api'),
    'version' => env('API_VERSION', 'api/v1'),
    'namespace' => env('API_NAMESPACE', 'V1'),
    'throttle' => 600000,
    //minutes
    'token_expire_in' => 43200,
    //days
    'refresh_token_expire_in' => 60,
    'super-role' => [
        'super-admin'
    ],
];
