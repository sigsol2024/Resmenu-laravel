<?php



namespace App\Http\Middleware;



use App\Models\Restaurant;

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

            abort(403, 'No restaurant associated with your account.');

        }



        session(['restaurant_id' => $restaurantId]);

        $request->attributes->set('restaurant_id', $restaurantId);

        View::share('restaurant', Restaurant::findOrFail($restaurantId));



        return $next($request);

    }

}


