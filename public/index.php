<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// #region agent log (fef746) - web boot diagnostics
try {
    // Write into Laravel project root (sibling of public/).
    $logFile = dirname(__DIR__).DIRECTORY_SEPARATOR.'debug-fef746.log';
    $emit = static function (string $message, array $data = []) use ($logFile): void {
        // Never log secrets; keep data minimal.
        $payload = [
            'sessionId' => 'fef746',
            'runId' => 'pre-fix',
            'hypothesisId' => 'H_boot',
            'location' => 'public/index.php:boot',
            'message' => $message,
            'data' => $data,
            'timestamp' => (int) (microtime(true) * 1000),
        ];
        @file_put_contents($logFile, json_encode($payload, JSON_UNESCAPED_SLASHES).PHP_EOL, FILE_APPEND);
    };

    register_shutdown_function(static function () use ($emit): void {
        $e = error_get_last();
        if ($e !== null) {
            $emit('shutdown_error', [
                'type' => $e['type'] ?? null,
                'file' => basename((string) ($e['file'] ?? '')),
                'line' => $e['line'] ?? null,
                'message' => substr((string) ($e['message'] ?? ''), 0, 300),
            ]);
        } else {
            $emit('shutdown_ok');
        }
    });

    $emit('request_start', [
        'uri' => $_SERVER['REQUEST_URI'] ?? null,
        'method' => $_SERVER['REQUEST_METHOD'] ?? null,
        'sapi' => PHP_SAPI,
        'php_version' => PHP_VERSION,
        'cwd_basename' => basename((string) getcwd()),
        'autoload_exists' => file_exists(__DIR__.'/../vendor/autoload.php'),
        'bootstrap_exists' => file_exists(__DIR__.'/../bootstrap/app.php'),
        'storage_writable' => is_writable(__DIR__.'/../storage'),
        'bootstrap_cache_writable' => is_writable(__DIR__.'/../bootstrap/cache'),
    ]);
} catch (\Throwable) {
    // never block request on diagnostics
}
// #endregion agent log (fef746)

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
