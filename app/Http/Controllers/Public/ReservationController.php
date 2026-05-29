<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\CustomizationService;
use App\Services\ReservationBookingService;
use App\Services\ReservationSlotService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptions,
        private CustomizationService $customization,
        private ReservationBookingService $booking,
        private ReservationSlotService $slots,
    ) {}

    public function show(Request $request, string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $access = $this->subscriptions->checkAccess($restaurant->id);
        if (! $access['valid']) {
            return view('public.subscription-blocked', [
                'restaurant' => $restaurant,
                'access' => $access,
                'context' => 'Table reservations',
            ]);
        }

        if (! $this->subscriptions->hasFeatureAccess($restaurant->id, 'table_reservations')) {
            return view('public.subscription-blocked', [
                'restaurant' => $restaurant,
                'access' => [
                    'valid' => false,
                    'lockout_reason' => 'feature_not_in_plan',
                    'message' => 'Table reservations are not included on this plan.',
                    'subscription' => $this->subscriptions->getRestaurantSubscription($restaurant->id),
                ],
                'context' => 'Table reservations',
            ]);
        }

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email',
                'guest_phone' => 'required|string|max:50',
                'reservation_date' => 'required|date|after_or_equal:today',
                'reservation_time' => 'required|string',
                'party_size' => 'required|integer|min:1|max:10',
                'special_occasion' => 'nullable|string|max:50',
                'notes' => 'nullable|string|max:1000',
            ]);

            $phoneDigits = preg_replace('/\D/', '', $data['guest_phone']);
            if (strlen($phoneDigits) < 10 || strlen($phoneDigits) > 15) {
                return back()->withErrors(['guest_phone' => 'Please enter a valid phone number (10-15 digits).'])->withInput();
            }

            $result = $this->booking->create($restaurant->id, $data);
            if (! $result['success']) {
                return back()->withErrors(['reservation' => implode(' ', $result['errors'] ?? [])])->withInput();
            }

            if (! empty($result['checkout_url'])) {
                return redirect($result['checkout_url']);
            }

            return back()->with('success', true);
        }

        $custom = $this->customization->forRestaurant($restaurant);
        $primaryColor = is_array($custom) ? ($custom['primary_color'] ?? '#f20d0d') : ($custom->primary_color ?? '#f20d0d');
        $bgColor = is_array($custom) ? ($custom['background_color'] ?? '#f8f5f5') : ($custom->background_color ?? '#f8f5f5');
        $uploadBaseUrl = rtrim((string) config('resmenu.upload_url', url('/uploads')), '/');
        $selectedDate = old('reservation_date', $request->query('date', date('Y-m-d')));
        $slotPayload = $this->slots->slotsForDate($restaurant->id, $selectedDate);

        $heroBgImage = $restaurant->hero_image
            ? $uploadBaseUrl.'/heroes/'.$restaurant->hero_image
            : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1600&h=900&fit=crop';

        $depositAmount = (float) (DB::table('restaurant_reservation_settings')
            ->where('restaurant_id', $restaurant->id)
            ->value('deposit_amount') ?? 0);

        return view('public.reservation-booking', [
            'restaurant' => $restaurant,
            'restaurantName' => $restaurant->name,
            'success' => session('success') === true,
            'primaryColor' => $primaryColor,
            'bgColor' => $bgColor,
            'menuUrl' => url('/restaurant/'.$restaurant->slug),
            'uploadBaseUrl' => $uploadBaseUrl,
            'heroBgImage' => $heroBgImage,
            'depositAmount' => $depositAmount,
            'selectedDate' => $selectedDate,
            'timeSlots' => $slotPayload['slots'] ?? [],
            'minDate' => date('Y-m-d'),
        ]);
    }
}
