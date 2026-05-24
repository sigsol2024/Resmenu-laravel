<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSubmissionService
{
    public function __construct(
        private SubscriptionService $subscriptions,
        private MailService $mail,
    ) {}

    /**
     * @param  array<int, array{id?:int, quantity?:int}>  $cart
     * @param  array<string, mixed>  $customer
     * @return array{success:bool, order_id?:int, errors:list<string>}
     */
    public function createFromCart(int $restaurantId, array $cart, array $customer, float $deliveryFee = 0, float $taxRate = 0): array
    {
        $restaurant = Restaurant::find($restaurantId);
        if (! $restaurant) {
            return ['success' => false, 'errors' => ['Restaurant not found.']];
        }

        $access = $this->subscriptions->checkAccess($restaurantId);
        if (! $access['valid']) {
            return ['success' => false, 'errors' => [$access['message'] ?: 'Subscription required.']];
        }

        $errors = $this->validateCustomer($customer);
        if ($errors !== []) {
            return ['success' => false, 'errors' => $errors];
        }

        $priced = $this->validateCart($restaurantId, $cart);
        if (! $priced['success']) {
            return ['success' => false, 'errors' => $priced['errors']];
        }

        $subtotal = $priced['subtotal'];
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $deliveryFee + $tax;

        try {
            $orderId = DB::transaction(function () use ($restaurantId, $customer, $priced, $subtotal, $deliveryFee, $tax, $total) {
                $orderNumber = strtoupper(Str::random(8));
                $order = Order::query()->create([
                    'restaurant_id' => $restaurantId,
                    'order_number' => $orderNumber,
                    'customer_name' => $customer['customer_name'],
                    'customer_phone' => $customer['customer_phone'],
                    'customer_email' => $customer['customer_email'],
                    'delivery_address' => $customer['delivery_address'],
                    'payment_method' => $customer['payment_method'] ?? null,
                    'status' => 'pending',
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'tax' => $tax,
                    'total' => $total,
                ]);

                foreach ($priced['lines'] as $line) {
                    DB::table('order_items')->insert([
                        'order_id' => $order->id,
                        'menu_item_id' => $line['menu_item_id'],
                        'name' => $line['name'],
                        'price' => $line['price'],
                        'quantity' => $line['quantity'],
                    ]);
                }

                return (int) $order->id;
            });

            $this->mail->sendOrderCreated($orderId, $restaurantId);

            return ['success' => true, 'order_id' => $orderId, 'errors' => []];
        } catch (\Throwable $e) {
            report($e);

            return ['success' => false, 'errors' => ['Unable to create order. Please try again.']];
        }
    }

    /** @return list<string> */
    private function validateCustomer(array $customer): array
    {
        $errors = [];
        if (trim((string) ($customer['customer_name'] ?? '')) === '') {
            $errors[] = 'Full name is required.';
        }
        if (trim((string) ($customer['customer_phone'] ?? '')) === '') {
            $errors[] = 'Phone number is required.';
        }
        $email = trim((string) ($customer['customer_email'] ?? ''));
        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email address is required.';
        }
        if (trim((string) ($customer['delivery_address'] ?? '')) === '') {
            $errors[] = 'Delivery address is required.';
        }

        return $errors;
    }

    /**
     * @param  array<int, array{id?:int, quantity?:int}>  $cart
     * @return array{success:bool, subtotal:float, lines:list<array{menu_item_id:int,name:string,price:float,quantity:int}>, errors:list<string>}
     */
    public function validateCartPublic(int $restaurantId, array $cart): array
    {
        return $this->validateCart($restaurantId, $cart);
    }

    /**
     * @param  array<int, array{id?:int, quantity?:int}>  $cart
     * @return array{success:bool, subtotal:float, lines:list<array{menu_item_id:int,name:string,price:float,quantity:int}>, errors:list<string>}
     */
    private function validateCart(int $restaurantId, array $cart): array
    {
        if ($cart === []) {
            return ['success' => false, 'subtotal' => 0, 'lines' => [], 'errors' => ['Cart is empty.']];
        }

        $prices = MenuItem::query()
            ->where('restaurant_id', $restaurantId)
            ->where('is_available', 1)
            ->get(['id', 'name', 'price'])
            ->keyBy('id');

        $lines = [];
        $subtotal = 0.0;

        foreach ($cart as $item) {
            $menuItemId = (int) ($item['id'] ?? 0);
            $row = $prices->get($menuItemId);
            if (! $row) {
                return ['success' => false, 'subtotal' => 0, 'lines' => [], 'errors' => ['Invalid or inactive menu item.']];
            }
            $quantity = min(max(1, (int) ($item['quantity'] ?? 1)), 99);
            $price = (float) $row->price;
            $lines[] = [
                'menu_item_id' => $menuItemId,
                'name' => $row->name,
                'price' => $price,
                'quantity' => $quantity,
            ];
            $subtotal += $price * $quantity;
        }

        return ['success' => true, 'subtotal' => $subtotal, 'lines' => $lines, 'errors' => []];
    }
}
