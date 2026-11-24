<?php

return [
    'nest' => [
        'url' => env('SERVICES_NEST_URL', 'http://localhost:3000/api'),
        'username' => env('SERVICES_NEST_USERNAME'),
        'password' => env('SERVICES_NEST_PASSWORD'),
        'timeout' => 8,
    ],
];
