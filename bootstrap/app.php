<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // IMPORTANT: During bootstrap, the config repository is not yet bound.
        // Using config() here can crash with "Target class [config] does not exist."
        // Read directly from env instead.
        if (filter_var(env('TRUST_PROXY_HEADERS', false), FILTER_VALIDATE_BOOLEAN)) {
            $middleware->trustProxies(at: '*');
        }

        $middleware->alias([
            'manager.tenant' => \App\Http\Middleware\EnsureManagerRestaurant::class,
            'session.idle' => \App\Http\Middleware\SessionIdleTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
