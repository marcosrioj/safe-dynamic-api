<?php

$allowedOrigins = array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', '*')));
$allowHeaders = array_map('trim', explode(',', env('CORS_ALLOWED_HEADERS', 'Content-Type, X-XSRF-TOKEN, X-Authorization')));
$allowedOriginsPatterns = array_map('trim', explode(',', env('CORS_CORS_ALLOWED_ORIGINS_PATTERNS', '')));
$allowMethods = array_map('trim', explode(',', env('CORS_ALLOWED_METHODS', 'OPTIONS, GET, PUT, POST, DELETE, PATCH')));
$allowCredentials = env('CORS_ALLOW_CREDENTIALS', false);
$exposeHeaders = array_map('trim', explode(',', env('CORS_EXPOSE_HEADERS', '')));
$maxAge = env('CORS_MAX_AGE', 1728000);


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

    'paths' => ['api/*'],

    'allowed_methods' => $allowMethods,

    'allowed_origins' => $allowedOrigins,

    'allowed_origins_patterns' => $allowedOriginsPatterns,

    'allowed_headers' => $allowHeaders,

    'exposed_headers' => $exposeHeaders,

    'max_age' => $maxAge,

    'supports_credentials' => $allowCredentials,

];
