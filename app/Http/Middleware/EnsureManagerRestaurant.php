<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use App\Support\ManagerImpersonation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureManagerRestaurant
{
    public function handle(Request $request, Closure $next): Response
    {
        $manager = Auth::guard('manager')->user();
        if (! $manager) {
            return redirect()->route('login');
        }

        $restaurantId = (int) $manager->restaurant_id;
        if ($restaurantId <= 0) {
            if (ManagerImpersonation::active($request)) {
                return ManagerImpersonation::restoreAdmin(
                    $request,
                    'Manager has no restaurant. Returned to administrator.',
                    'missing_restaurant_id'
                );
            }
            abort(403, 'No restaurant associated with your account.');
        }

        $restaurant = Restaurant::find($restaurantId);
        if (! $restaurant) {
            if (ManagerImpersonation::active($request)) {
                return ManagerImpersonation::restoreAdmin(
                    $request,
                    'Restaurant no longer exists. Returned to administrator.',
                    'restaurant_missing'
                );
            }
            abort(403, 'No restaurant associated with your account.');
        }

        if ($restaurant->isSuspended()) {
            if (ManagerImpersonation::active($request)) {
                return ManagerImpersonation::restoreAdmin(
                    $request,
                    'Restaurant is suspended. Returned to administrator.',
                    'restaurant_suspended'
                );
            }

            Auth::guard('manager')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'username' => 'This restaurant account is suspended. Contact support for help.',
            ]);
        }

        session(['restaurant_id' => $restaurantId]);
        $request->attributes->set('restaurant_id', $restaurantId);
        View::share('restaurant', $restaurant);

        return $next($request);
    }
}
