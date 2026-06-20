<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $routeName = $request->route()?->getName();
        $isPublicMenuRoute = in_array($routeName, ['public.menu', 'public.menu.section', 'public.menu.category'], true);

        if ($isPublicMenuRoute) {
            // Allow iframe embedding only for public menu routes, and only from configured parent origins.
            // Note: X-Frame-Options would block cross-origin iframes even if CSP allows it, so we remove it here.
            $response->headers->remove('X-Frame-Options');

            $originsRaw = (string) env('IFRAME_ALLOWED_ORIGINS', '');
            $origins = array_values(array_filter(array_map('trim', explode(',', $originsRaw))));

            // If not configured, default to 'self' only (no cross-origin iframes).
            // Use "*" to allow any origin (not recommended).
            if ($origins === []) {
                $frameAncestors = ["'self'"];
            } elseif (count($origins) === 1 && $origins[0] === '*') {
                $frameAncestors = ['*'];
            } else {
                $frameAncestors = array_merge(["'self'"], $origins);
            }

            $cspValue = 'frame-ancestors '.implode(' ', $frameAncestors).';';
            $response->headers->set('Content-Security-Policy', $cspValue);
        } else {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        if ($request->secure() && app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
