<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\TableReservation;
use App\Support\ReservationConfirmationAccess;
use App\Support\ReservationNumberGenerator;
use Illuminate\Support\Facades\DB;

class ReservationBookingService
{
    public function __construct(
        private SubscriptionService $subscriptions,
        private RestaurantTransactionalMailService $mail,
        private ManagerFeatureAccess $features,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return array{success:bool, errors?:list<string>, message?:string, reservation_id?:int, checkout_url?:string, confirmation_url?:string}
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

        if (! $this->features->tableReservationsUsable($restaurantId)) {
            return ['success' => false, 'errors' => ['Table reservations are not available for this restaurant.']];
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

        if ($deposit > 0) {
            return [
                'success' => true,
                'reservation_id' => (int) $reservation->id,
                'checkout_url' => route('public.checkout', [
                    'slug' => $restaurant->slug,
                    'reservation_id' => $reservation->id,
                ]),
            ];
        }

        try {
            $this->mail->sendReservationCreated($reservation->id, $restaurantId);
        } catch (\Throwable $e) {
            report($e);
        }

        return [
            'success' => true,
            'reservation_id' => (int) $reservation->id,
            'message' => "We've received your reservation and our team will get back to you.",
            'confirmation_url' => ReservationConfirmationAccess::url((int) $reservation->id, (string) $restaurant->slug),
        ];
    }
}
