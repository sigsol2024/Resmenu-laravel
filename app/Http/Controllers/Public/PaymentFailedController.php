<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\CustomizationService;
use Illuminate\Http\Request;

class PaymentFailedController extends Controller
{
    public function __construct(private CustomizationService $customization) {}

    public function show(Request $request)
    {
        $slug = trim((string) $request->query('slug', ''));
        $reason = trim((string) $request->query('reason', 'failed'));

        $restaurantName = 'Restaurant';
        $primaryColor = '#f20d0d';
        $menuUrl = route('login');

        if ($slug !== '') {
            $restaurant = Restaurant::where('slug', $slug)->first();
            if ($restaurant) {
                $restaurantName = $restaurant->name;
                $custom = $this->customization->forRestaurant($restaurant);
                $primaryColor = $custom['primary_color'] ?? '#f20d0d';
                $menuUrl = route('public.menu', $restaurant->slug);
            }
        }

        $message = match ($reason) {
            'cancelled' => 'Payment was cancelled. No order was placed.',
            'failed' => 'Payment failed. No order was placed. Please try again.',
            'init_failed' => 'Payment could not be initiated. Please try again.',
            default => $request->query('message', 'Something went wrong. No order was placed. Please try again.'),
        };

        return view('public.payment-failed', compact(
            'message',
            'restaurantName',
            'primaryColor',
            'menuUrl',
        ));
    }
}
