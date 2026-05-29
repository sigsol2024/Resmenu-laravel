<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\TableReservation;
use App\Support\ReservationNumberGenerator;
use Illuminate\Support\Facades\DB;

class ReservationBookingService
{
    public function __construct(
        private SubscriptionService $subscriptions,
        private RestaurantTransactionalMailService $mail,
    ) {}

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

        if (! $this->subscriptions->hasFeatureAccess($restaurantId, 'table_reservations')) {
            return ['success' => false, 'errors' => ['Table reservations are not available on this plan.']];
        }

        $deposit = (float) (DB::table('restaurant_reservation_settings')
            ->where('restaurant_id', $restaurantId)
            ->value('deposit_amount') ?? 0);

        $time = (string) $data['reservation_time'];
        if (strlen($time) === 5) {
            $time .= ':00';
        }

        $reservation = TableReservation::create([
            'restaurant_id' => $restaurantId,
            'reservation_number' => ReservationNumberGenerator::generate(),
            'status' => 'pending',
            'guest_name' => $data['guest_name'],
            'guest_email' => $data['guest_email'],
            'guest_phone' => $data['guest_phone'],
            'reservation_date' => $data['reservation_date'],
            'reservation_time' => $time,
            'party_size' => (int) $data['party_size'],
            'special_occasion' => $data['special_occasion'] ?? null,
            'deposit_amount' => $deposit,
            'deposit_paid' => false,
            'notes' => $data['notes'] ?? null,
        ]);

        try {
            $this->mail->sendReservationCreated($reservation->id, $restaurantId);
        } catch (\Throwable $e) {
            report($e);
        }

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
