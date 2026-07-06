<?php

$normalizeCorsOrigin = static fn (string $origin): string => preg_replace(
    '#(?<!:)/+$#',
    '',
    trim($origin)
) ?? trim($origin);

$configuredCorsOrigins = explode(',', env('CORS_ALLOWED_ORIGINS', env('FRONTEND_URL', 'http://localhost:4321')));

$productionCorsOrigins = [
    'https://zillenialaction.id',
    'https://www.zillenialaction.id',
    'https://sostrip.zillenialaction.id',
];

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_values(array_unique(array_filter(array_map(
        $normalizeCorsOrigin,
        array_merge($configuredCorsOrigins, $productionCorsOrigins)
    )))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
