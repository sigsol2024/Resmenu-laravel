<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function __construct(private SubscriptionService $subscriptions) {}

    public function handle(Request $request, Closure $next): Response
    {
        $restaurantId = (int) $request->attributes->get('restaurant_id');
        if ($restaurantId <= 0) {
            return $next($request);
        }

        $access = $this->subscriptions->checkAccess($restaurantId);
        if ($access['valid']) {
            return $next($request);
        }

        $routeName = (string) ($request->route()?->getName() ?? '');

        if (str_starts_with($routeName, 'manager.billing.') || str_starts_with($routeName, 'manager.bank-transfers.') || $routeName === 'logout') {
            return $next($request);
        }

        if ($routeName === 'manager.dashboard' && $request->isMethod('GET')) {
            return $next($request);
        }

        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return redirect()->route('manager.billing.index', ['upgrade_required' => 1]);
        }

        if ($routeName === 'manager.dashboard') {
            return $next($request);
        }

        return redirect()->route('manager.billing.index', ['upgrade_required' => 1]);
    }
}
