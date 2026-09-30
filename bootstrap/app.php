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
        // Trust proxies when explicitly enabled, or when APP_URL is HTTPS (common shared-host SSL).
        // Without this, signed magic-link URLs fail with "Invalid signature" (http vs https mismatch).
        $appUrl = (string) env('APP_URL', '');
        $trustProxies = filter_var(env('TRUST_PROXY_HEADERS', false), FILTER_VALIDATE_BOOLEAN)
            || str_starts_with($appUrl, 'https://');
        if ($trustProxies) {
            $middleware->trustProxies(at: '*');
        }

        $middleware->alias([
            'manager.tenant' => \App\Http\Middleware\EnsureManagerRestaurant::class,
            'session.idle' => \App\Http\Middleware\SessionIdleTimeout::class,
            'subscription.active' => \App\Http\Middleware\EnsureActiveSubscription::class,
            'manager.email.verified' => \App\Http\Middleware\EnsureManagerEmailVerified::class,
            'super.admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'admin.active' => \App\Http\Middleware\EnsureAdminActive::class,
            'admin.permission' => \App\Http\Middleware\EnsureAdminPermission::class,
        ]);

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Expired/missing session makes the CSRF token stale. Show a useful redirect instead of the bare "419 Page Expired".
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() !== 419 || ! ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException)) {
                return null;
            }

            $authArea = $request->is('manager', 'manager/*', 'admin', 'admin/*', 'logout', 'impersonation/*');

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your session has expired. Please refresh the page and try again.',
                    'redirect' => $authArea ? route('login') : null,
                ], 419);
            }

            if ($authArea) {
                foreach (['manager', 'admin'] as $guard) {
                    \Illuminate\Support\Facades\Auth::guard($guard)->logout();
                }
                if ($request->hasSession()) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                }

                return redirect()->route('login')
                    ->with('error', 'Your session has expired. Please log in again.');
            }

            return redirect()->back()
                ->withInput($request->except(['_token', 'password', 'password_confirm', 'password_confirmation', 'current_password']))
                ->withErrors(['session' => 'This page expired. Please try again.']);
        });
    })->create();
