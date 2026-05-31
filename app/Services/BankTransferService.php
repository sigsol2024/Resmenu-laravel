<?php

namespace App\Services;

use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\TableReservation;
use App\Support\OrderConfirmationToken;
use App\Support\ReservationConfirmationToken;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankTransferService
{
    private const WINDOW_SECONDS = 900;

    public function __construct(
        private OrderSubmissionService $orders,
        private MailService $mail,
    ) {}

    /**
     * @param  array<int, array<string, mixed>>  $cart
     * @param  array<string, mixed>  $customer
     * @return array{success:bool, message?:string, redirect?:string, token?:string}
     */
    public function createDraftFromCart(
        int $restaurantId,
        array $cart,
        array $customer,
        float $deliveryFee = 0,
        float $taxRate = 0,
    ): array {
        $priced = $this->orders->validateCartPublic($restaurantId, $cart);
        if (! $priced['success']) {
            return ['success' => false, 'message' => implode(' ', $priced['errors'] ?? ['Invalid cart.'])];
        }

        $subtotal = $priced['subtotal'];
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $deliveryFee + $tax;
        $token = bin2hex(random_bytes(24));

        DB::table('pending_bank_transfers')->insert([
            'token' => $token,
            'restaurant_id' => $restaurantId,
            'payment_type' => 'order',
            'reservation_id' => null,
            'cart_json' => json_encode($priced['lines']),
            'customer_name' => $customer['customer_name'] ?? '',
            'customer_phone' => $customer['customer_phone'] ?? '',
            'customer_email' => $customer['customer_email'] ?? '',
            'delivery_address' => $customer['delivery_address'] ?? '',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return [
            'success' => true,
            'token' => $token,
            'redirect' => route('public.bank-transfer-pending', ['token' => $token]),
        ];
    }

    /**
     * @param  array<string, mixed>  $customer
     * @return array{success:bool, message?:string, redirect?:string, token?:string}
     */
    public function createDraftForReservation(int $restaurantId, TableReservation $reservation, array $customer): array
    {
        $deposit = (float) ($reservation->deposit_amount ?? 0);
        if ($deposit <= 0) {
            return ['success' => false, 'message' => 'No deposit required for this reservation.'];
        }

        $token = bin2hex(random_bytes(24));

        DB::table('pending_bank_transfers')->insert([
            'token' => $token,
            'restaurant_id' => $restaurantId,
            'payment_type' => 'reservation',
            'reservation_id' => $reservation->id,
            'cart_json' => '[]',
            'customer_name' => $customer['customer_name'] ?? $reservation->guest_name,
            'customer_phone' => $customer['customer_phone'] ?? $reservation->guest_phone,
            'customer_email' => $customer['customer_email'] ?? $reservation->guest_email,
            'delivery_address' => 'Reservation #'.$reservation->id,
            'subtotal' => $deposit,
            'delivery_fee' => 0,
            'tax' => 0,
            'total' => $deposit,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return [
            'success' => true,
            'token' => $token,
            'redirect' => route('public.bank-transfer-pending', ['token' => $token]),
        ];
    }

    /** @return array{success:bool, message?:string, redirect?:string} */
    public function customerClaimPayment(string $token): array
    {
        $token = trim($token);
        if ($token === '') {
            return ['success' => false, 'message' => 'Invalid request.'];
        }

        $draft = DB::table('pending_bank_transfers')->where('token', $token)->first();
        if (! $draft) {
            return ['success' => false, 'message' => 'Session expired. Please place a new order.'];
        }

        if (in_array($draft->status ?? 'pending', ['approved', 'expired', 'cancelled'], true)) {
            return ['success' => false, 'message' => 'This payment request is no longer active.'];
        }

        if ($this->isExpired($draft)) {
            DB::table('pending_bank_transfers')->where('id', $draft->id)->update(['status' => 'expired']);

            return ['success' => false, 'message' => 'Payment window has expired. Please place a new order.'];
        }

        if (($draft->status ?? 'pending') === 'customer_claimed') {
            return ['success' => true, 'message' => 'Payment claim already recorded. Awaiting restaurant approval.'];
        }

        DB::table('pending_bank_transfers')->where('id', $draft->id)->update([
            'status' => 'customer_claimed',
            'customer_claimed_at' => now(),
        ]);

        $this->notifyManagerOfClaim($draft);

        Log::info('Bank transfer claimed by customer', [
            'draft_id' => $draft->id,
            'restaurant_id' => $draft->restaurant_id,
            'payment_type' => $draft->payment_type,
        ]);

        return [
            'success' => true,
            'message' => 'Thank you. The restaurant will confirm your payment shortly.',
        ];
    }

    /** @return array{success:bool, message?:string, redirect?:string} */
    public function managerApprove(int $draftId, int $managerId, int $restaurantId): array
    {
        return DB::transaction(function () use ($draftId, $managerId, $restaurantId) {
            $draft = DB::table('pending_bank_transfers')
                ->where('id', $draftId)
                ->where('restaurant_id', $restaurantId)
                ->lockForUpdate()
                ->first();

            if (! $draft) {
                return ['success' => false, 'message' => 'Transfer request not found.'];
            }

            if (! in_array($draft->status ?? '', ['pending', 'customer_claimed'], true)) {
                return ['success' => false, 'message' => 'This transfer cannot be approved.'];
            }

            if ($this->isExpired($draft)) {
                DB::table('pending_bank_transfers')->where('id', $draft->id)->update(['status' => 'expired']);

                return ['success' => false, 'message' => 'Payment window has expired.'];
            }

            $restaurant = Restaurant::find($restaurantId);
            $slug = $restaurant?->slug ?? '';
            $redirect = '';

            if (($draft->payment_type ?? 'order') === 'reservation' && ! empty($draft->reservation_id)) {
                TableReservation::query()
                    ->where('id', (int) $draft->reservation_id)
                    ->where('restaurant_id', $restaurantId)
                    ->update(['deposit_paid' => true, 'status' => 'confirmed', 'updated_at' => now()]);

                $redirect = ReservationConfirmationToken::confirmationUrl((int) $draft->reservation_id, $slug);
            } else {
                $cart = json_decode((string) ($draft->cart_json ?? '[]'), true) ?: [];
                $cartItems = array_map(fn ($line) => [
                    'id' => $line['menu_item_id'] ?? $line['id'] ?? 0,
                    'quantity' => $line['quantity'] ?? 1,
                ], $cart);

                $subtotal = (float) $draft->subtotal;
                $taxRate = $subtotal > 0 ? (float) $draft->tax / $subtotal : 0;

                $result = $this->orders->createFromCart($restaurantId, $cartItems, [
                    'customer_name' => $draft->customer_name,
                    'customer_phone' => $draft->customer_phone,
                    'customer_email' => $draft->customer_email,
                    'delivery_address' => $draft->delivery_address,
                    'payment_method' => 'bank_transfer',
                ], (float) $draft->delivery_fee, $taxRate);

                if (! ($result['success'] ?? false)) {
                    throw new \RuntimeException(implode(' ', $result['errors'] ?? ['Failed to create order.']));
                }

                DB::table('orders')->where('id', $result['order_id'])->update([
                    'status' => 'confirmed',
                    'updated_at' => now(),
                ]);

                $redirect = OrderConfirmationToken::confirmationUrl((int) $result['order_id'], $slug);
            }

            DB::table('pending_bank_transfers')->where('id', $draft->id)->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by_manager_id' => $managerId,
            ]);

            Log::info('Bank transfer approved by manager', [
                'draft_id' => $draft->id,
                'manager_id' => $managerId,
                'restaurant_id' => $restaurantId,
            ]);

            return [
                'success' => true,
                'message' => 'Payment approved.',
                'redirect' => $redirect !== '' ? $redirect : null,
            ];
        });
    }

    public function managerReject(int $draftId, int $managerId, int $restaurantId): bool
    {
        $updated = DB::table('pending_bank_transfers')
            ->where('id', $draftId)
            ->where('restaurant_id', $restaurantId)
            ->whereIn('status', ['pending', 'customer_claimed'])
            ->update(['status' => 'cancelled']);

        if ($updated) {
            Log::info('Bank transfer rejected by manager', [
                'draft_id' => $draftId,
                'manager_id' => $managerId,
                'restaurant_id' => $restaurantId,
            ]);
        }

        return $updated > 0;
    }

    /** @return Collection<int, object> */
    public function listPendingForRestaurant(int $restaurantId): Collection
    {
        return DB::table('pending_bank_transfers')
            ->where('restaurant_id', $restaurantId)
            ->whereIn('status', ['pending', 'customer_claimed'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function expireDraft(string $token): bool
    {
        return DB::table('pending_bank_transfers')
            ->where('token', trim($token))
            ->whereIn('status', ['pending', 'customer_claimed'])
            ->update(['status' => 'expired']) > 0;
    }

    public function cancelOrderDraft(string $token): bool
    {
        return DB::table('pending_bank_transfers')
            ->where('token', trim($token))
            ->whereIn('status', ['pending', 'customer_claimed'])
            ->update(['status' => 'cancelled']) > 0;
    }

    /** @deprecated Use customerClaimPayment() */
    public function confirm(string $token): array
    {
        return $this->customerClaimPayment($token);
    }

    private function isExpired(object $draft): bool
    {
        return time() - strtotime((string) $draft->created_at) > self::WINDOW_SECONDS;
    }

    private function notifyManagerOfClaim(object $draft): void
    {
        $manager = Manager::where('restaurant_id', (int) $draft->restaurant_id)->where('is_active', 1)->first();
        if (! $manager || ! filter_var($manager->email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $restaurant = Restaurant::find((int) $draft->restaurant_id);
        $name = $restaurant?->name ?? 'Restaurant';
        $url = route('manager.bank-transfers.index');
        $html = '<p>A customer marked a bank transfer as sent for <strong>'.e($name).'</strong>.</p>'
            .'<p>Customer: '.e($draft->customer_name).'<br>Amount: ₦'.number_format((float) $draft->total, 2).'</p>'
            .'<p><a href="'.e($url).'">Review pending bank transfers</a></p>';

        $this->mail->send($manager->email, $manager->username ?: $manager->email, 'Bank transfer awaiting approval - '.$name, $html);
    }
}
