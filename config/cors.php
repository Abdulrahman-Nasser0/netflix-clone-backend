<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:5173',
        'http://localhost:3000',
        'http://localhost:5174',
        'https://netflix-clone-lemon-nu-42.vercel.app',
        'https://spontaneous-dragon-0b35f1.netlify.app/',
        'https://*.netlify.app/',
        'https://*.vercel.app/',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
