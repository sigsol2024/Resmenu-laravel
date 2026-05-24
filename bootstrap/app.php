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
        if (config('resmenu.trust_proxy_headers')) {
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
