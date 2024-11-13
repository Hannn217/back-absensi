<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Pastikan 'sanctum/csrf-cookie' ada di sini
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173'], // Domain frontend Anda
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Penting untuk mendukung cookie dan sesi
];
