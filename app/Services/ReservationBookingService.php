<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\TableReservation;
use Illuminate\Support\Facades\DB;

class ReservationBookingService
{
    public function __construct(private SubscriptionService $subscriptions) {}

    /**
     * @param  array<string, mixed>  $data
     * @return array{success:bool, errors?:list<string>, message?:string, checkout_url?:string}
     */
    public function create(int $restaurantId, array $data): array
    {
        $restaurant = Restaurant::find($restaurantId);
        if (! $restaurant) {
            return ['success' => false, 'errors' => ['Restaurant not found.']];
        }

        $access = $this->subscriptions->checkAccess($restaurantId);
        if (! $access['valid']) {
            return ['success' => false, 'errors' => [$access['message'] ?: 'Subscription required.']];
        }

        $deposit = (float) (DB::table('restaurant_settings')
            ->where('restaurant_id', $restaurantId)
            ->value('reservation_deposit_amount') ?? 0);

        $reservation = TableReservation::create([
            'restaurant_id' => $restaurantId,
            'status' => 'pending',
            'guest_name' => $data['guest_name'],
            'guest_email' => $data['guest_email'],
            'guest_phone' => $data['guest_phone'],
            'reservation_date' => $data['reservation_date'],
            'reservation_time' => $data['reservation_time'],
            'party_size' => (int) $data['party_size'],
            'deposit_amount' => $deposit,
            'deposit_paid' => false,
            'notes' => $data['notes'] ?? null,
        ]);

        if ($deposit > 0) {
            return [
                'success' => true,
                'checkout_url' => route('public.checkout', [
                    'slug' => $restaurant->slug,
                    'reservation_id' => $reservation->id,
                ]),
            ];
        }

        return ['success' => true, 'message' => 'Reservation request received. We will confirm shortly.'];
    }
}
