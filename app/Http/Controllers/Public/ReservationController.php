<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\CustomizationService;
use App\Services\ReservationBookingService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptions,
        private CustomizationService $customization,
        private ReservationBookingService $booking,
    ) {}

    public function show(Request $request, string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $access = $this->subscriptions->checkAccess($restaurant->id);
        if (! $access['valid']) {
            return view('public.subscription-blocked', [
                'restaurant' => $restaurant,
                'access' => $access,
                'context' => 'Reservations',
            ]);
        }

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email',
                'guest_phone' => 'required|string|max:50',
                'reservation_date' => 'required|date',
                'reservation_time' => 'required|string',
                'party_size' => 'required|integer|min:1|max:50',
                'notes' => 'nullable|string|max:1000',
            ]);

            $result = $this->booking->create($restaurant->id, $data);
            if (! $result['success']) {
                return back()->withErrors(['reservation' => implode(' ', $result['errors'])])->withInput();
            }

            if (! empty($result['checkout_url'])) {
                return redirect($result['checkout_url']);
            }

            return back()->with('success', $result['message'] ?? 'Reservation submitted.');
        }

        return view('public.reservation', [
            'restaurant' => $restaurant,
            'customization' => $this->customization->forRestaurant($restaurant),
        ]);
    }
}
