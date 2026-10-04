<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:4200',
        'http://127.0.0.1:4200',
        'https://localhost:4200',
        'https://greenjackshop.com',
        'http://greenjackshop.com',
        'https://portal.greenjackshop.com',
        'http://portal.greenjackshop.com',
    ],
    'allowed_origins_patterns' => [
        '/^http:\/\/localhost:\d+$/',
        '/^https:\/\/.*\.greenjackshop\.com$/',
        '/^http:\/\/.*\.greenjackshop\.com$/',
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];