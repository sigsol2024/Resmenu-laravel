<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\TableReservation;
use Illuminate\Support\Facades\DB;

class RestaurantPaymentService
{
    public function __construct(
        private PendingOnlinePaymentService $pending,
        private PaymentGatewayService $gateway,
    ) {}

    /** @return list<array{code:string,label:string}> */
    public function activeMethods(int $restaurantId): array
    {
        $rows = DB::table('restaurant_payment_settings')
            ->where('restaurant_id', $restaurantId)
            ->where('is_active', 1)
            ->get();

        if ($rows->isEmpty()) {
            return [
                ['code' => 'cash', 'label' => 'Pay on delivery'],
            ];
        }

        $methods = [];
        foreach ($rows as $row) {
            $label = match ($row->gateway) {
                'paystack' => 'Pay with Paystack',
                'flutterwave' => 'Pay with Flutterwave',
                'bank_transfer' => 'Bank transfer',
                'cash' => 'Pay on delivery',
                default => ucfirst(str_replace('_', ' ', (string) $row->gateway)),
            };
            $methods[] = ['code' => $row->gateway, 'label' => $label];
        }

        return $methods;
    }

    /**
     * @param  array<int, array<string, mixed>>  $cart
     * @param  array<string, mixed>  $customer
     */
    public function initiateOrderPayment(
        Restaurant $restaurant,
        array $cart,
        array $customer,
        string $gateway,
    ): array {
        $pending = $this->pending->createPendingOrder(
            $restaurant->id,
            $cart,
            $customer,
            $gateway,
        );

        if (! $pending['success']) {
            return ['success' => false, 'errors' => $pending['errors'] ?? ['Unable to start payment']];
        }

        $init = $this->gateway->initializeRestaurantPayment(
            $restaurant->id,
            $gateway,
            (float) $pending['total'],
            (string) $pending['email'],
            (string) $pending['reference'],
            [
                'reference' => $pending['reference'],
                'customer_name' => $customer['customer_name'] ?? '',
            ],
            $restaurant->slug,
        );

        if (empty($init['redirect_url'])) {
            $this->pending->discardFailed($pending['reference'], $gateway);

            return ['success' => false, 'errors' => [$init['error'] ?? 'Payment initialization failed']];
        }

        return ['success' => true, 'redirect_url' => $init['redirect_url']];
    }

    /**
     * @param  array<string, mixed>  $customer
     */
    public function initiateReservationDeposit(Restaurant $restaurant, TableReservation $reservation, array $customer): array
    {
        $gateway = $customer['payment_method'] ?? 'paystack';
        if (! in_array($gateway, ['paystack', 'flutterwave'], true)) {
            return [];
        }

        $reference = 'POP_'.time().'_'.bin2hex(random_bytes(4));
        DB::table('pending_online_payments')->insert([
            'reference' => $reference,
            'restaurant_id' => $restaurant->id,
            'payment_type' => 'reservation',
            'reservation_id' => $reservation->id,
            'gateway' => $gateway,
            'cart_json' => '[]',
            'customer_name' => $customer['customer_name'],
            'customer_phone' => $customer['customer_phone'],
            'customer_email' => $customer['customer_email'],
            'delivery_address' => 'Reservation #'.$reservation->id,
            'subtotal' => $reservation->deposit_amount,
            'delivery_fee' => 0,
            'tax' => 0,
            'total' => $reservation->deposit_amount,
            'created_at' => now(),
        ]);

        $init = $this->gateway->initializeRestaurantPayment(
            $restaurant->id,
            $gateway,
            (float) $reservation->deposit_amount,
            $customer['customer_email'],
            $reference,
            ['reservation_id' => $reservation->id],
            $restaurant->slug,
        );

        return $init;
    }
}
