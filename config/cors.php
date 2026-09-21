<?php

$raw = (string) env('CORS_ALLOWED_ORIGINS', '');
$origins = array_values(array_filter(array_map('trim', explode(',', $raw)), fn ($o) => $o !== '' && $o !== '*'));

return [

    'paths' => ['api/leads', 'api/crm/public-config'],

    'allowed_methods' => ['GET', 'POST', 'OPTIONS'],

    // Explicit allowlist only — never '*'. Empty list denies cross-origin.
    'allowed_origins' => $origins,

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'Accept', 'X-Requested-With'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
