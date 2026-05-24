<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\TableReservation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PendingOnlinePaymentService
{
    public function __construct(
        private OrderSubmissionService $orders,
    ) {}

    /**
     * @param  array<int, array<string, mixed>>  $cart
     * @param  array<string, mixed>  $customer
     */
    public function createPendingOrder(
        int $restaurantId,
        array $cart,
        array $customer,
        string $gateway,
        float $deliveryFee = 0,
        float $taxRate = 0,
    ): array {
        $priced = $this->orders->validateCartPublic($restaurantId, $cart);
        if (! $priced['success']) {
            return ['success' => false, 'errors' => $priced['errors']];
        }

        $subtotal = $priced['subtotal'];
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $deliveryFee + $tax;
        $reference = 'POP_'.time().'_'.Str::lower(Str::random(8));

        DB::table('pending_online_payments')->insert([
            'reference' => $reference,
            'restaurant_id' => $restaurantId,
            'payment_type' => 'order',
            'reservation_id' => null,
            'gateway' => $gateway,
            'cart_json' => json_encode($priced['lines']),
            'customer_name' => $customer['customer_name'],
            'customer_phone' => $customer['customer_phone'],
            'customer_email' => $customer['customer_email'],
            'delivery_address' => $customer['delivery_address'],
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'tax' => $tax,
            'total' => $total,
            'created_at' => now(),
        ]);

        return [
            'success' => true,
            'reference' => $reference,
            'total' => $total,
            'email' => $customer['customer_email'],
            'restaurant_id' => $restaurantId,
        ];
    }

    public function fulfillFromWebhook(string $reference, string $gateway): array
    {
        $cacheKey = 'webhook_fulfilled:'.$gateway.':'.$reference;
        if (! Cache::add($cacheKey, 1, now()->addDays(7))) {
            return ['success' => true, 'already_processed' => true];
        }

        try {
            return DB::transaction(function () use ($reference, $gateway) {
                $draft = DB::table('pending_online_payments')
                    ->where('reference', $reference)
                    ->where('gateway', $gateway)
                    ->lockForUpdate()
                    ->first();

                if (! $draft) {
                    return ['success' => true, 'already_processed' => true];
                }

                if (($draft->payment_type ?? 'order') === 'reservation' && $draft->reservation_id) {
                    TableReservation::where('id', $draft->reservation_id)
                        ->update(['deposit_paid' => true, 'status' => 'confirmed', 'updated_at' => now()]);
                    DB::table('pending_online_payments')->where('reference', $reference)->delete();

                    $restaurant = Restaurant::find($draft->restaurant_id);
                    $slug = $restaurant?->slug ?? '';
                    $this->rememberFulfillment($gateway, $reference, [
                        'type' => 'reservation',
                        'slug' => $slug,
                    ]);

                    return [
                        'success' => true,
                        'type' => 'reservation',
                        'slug' => $slug,
                    ];
                }

                $cart = json_decode($draft->cart_json ?? '[]', true) ?: [];
                $cartItems = array_map(fn ($line) => [
                    'id' => $line['menu_item_id'] ?? $line['id'] ?? 0,
                    'quantity' => $line['quantity'] ?? 1,
                ], $cart);

                $subtotal = (float) $draft->subtotal;
                $taxRate = $subtotal > 0 ? (float) $draft->tax / $subtotal : 0;

                $result = $this->orders->createFromCart((int) $draft->restaurant_id, $cartItems, [
                    'customer_name' => $draft->customer_name,
                    'customer_phone' => $draft->customer_phone,
                    'customer_email' => $draft->customer_email,
                    'delivery_address' => $draft->delivery_address,
                    'payment_method' => $gateway,
                ], (float) $draft->delivery_fee, $taxRate);

                if (! $result['success']) {
                    throw new \RuntimeException(implode(' ', $result['errors']));
                }

                Order::where('id', $result['order_id'])->update([
                    'status' => 'confirmed',
                    'updated_at' => now(),
                ]);

                DB::table('pending_online_payments')->where('reference', $reference)->delete();

                $restaurant = Restaurant::find($draft->restaurant_id);
                $slug = $restaurant?->slug ?? '';
                $this->rememberFulfillment($gateway, $reference, [
                    'order_id' => $result['order_id'],
                    'slug' => $slug,
                    'type' => 'order',
                ]);

                return [
                    'success' => true,
                    'order_id' => $result['order_id'],
                    'slug' => $slug,
                    'type' => 'order',
                ];
            });
        } catch (\Throwable $e) {
            Cache::forget($cacheKey);
            report($e);

            return ['success' => false, 'errors' => [$e->getMessage()]];
        }
    }

    public function discardFailed(string $reference, string $gateway): void
    {
        DB::table('pending_online_payments')
            ->where('reference', $reference)
            ->where('gateway', $gateway)
            ->delete();
    }

    /** @param  array{order_id?:int, slug?:string, type:string}  $payload */
    private function rememberFulfillment(string $gateway, string $reference, array $payload): void
    {
        Cache::put('fulfilled_order_ref:'.$gateway.':'.$reference, $payload, now()->addDays(7));
    }
}
