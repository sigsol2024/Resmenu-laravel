<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\TableReservation;
use App\Support\OrderConfirmationToken;
use Illuminate\Support\Facades\DB;

class BankTransferService
{
    private const WINDOW_SECONDS = 900;

    public function __construct(
        private OrderSubmissionService $orders,
        private MailService $mail,
    ) {}

    /** @return array{success:bool, message?:string, redirect?:string} */
    public function confirm(string $token): array
    {
        $token = trim($token);
        if ($token === '') {
            return ['success' => false, 'message' => 'Invalid request.'];
        }

        $draft = DB::table('pending_bank_transfers')->where('token', $token)->first();
        if (! $draft) {
            return ['success' => false, 'message' => 'Session expired. Please place a new order.'];
        }

        if (time() - strtotime((string) $draft->created_at) > self::WINDOW_SECONDS) {
            DB::table('pending_bank_transfers')->where('token', $token)->delete();

            return ['success' => false, 'message' => 'Payment window has expired. Please place a new order.'];
        }

        $restaurant = Restaurant::find((int) $draft->restaurant_id);
        $slug = $restaurant?->slug ?? '';

        if (($draft->payment_type ?? 'order') === 'reservation' && ! empty($draft->reservation_id)) {
            TableReservation::query()
                ->where('id', (int) $draft->reservation_id)
                ->where('restaurant_id', (int) $draft->restaurant_id)
                ->update(['deposit_paid' => 1, 'status' => 'confirmed', 'updated_at' => now()]);

            DB::table('pending_bank_transfers')->where('token', $token)->delete();

            return [
                'success' => true,
                'redirect' => route('public.reservation.confirmation', ['reservation' => (int) $draft->reservation_id]),
            ];
        }

        $cart = json_decode((string) ($draft->cart_json ?? '[]'), true);
        if (! is_array($cart)) {
            $cart = [];
        }

        $subtotal = (float) $draft->subtotal;
        $taxRate = $subtotal > 0 ? (float) $draft->tax / $subtotal : 0;

        $result = $this->orders->createFromCart(
            (int) $draft->restaurant_id,
            $cart,
            [
                'customer_name' => $draft->customer_name,
                'customer_phone' => $draft->customer_phone,
                'customer_email' => $draft->customer_email,
                'delivery_address' => $draft->delivery_address,
                'payment_method' => 'bank_transfer',
            ],
            (float) $draft->delivery_fee,
            $taxRate,
        );

        if (! $result['success']) {
            return ['success' => false, 'message' => $result['errors'][0] ?? 'Failed to create order.'];
        }

        DB::table('pending_bank_transfers')->where('token', $token)->delete();

        $orderId = (int) $result['order_id'];
        $redirect = OrderConfirmationToken::confirmationUrl($orderId, $slug);
        if ($redirect === '') {
            $redirect = route('public.order.confirmation', ['order' => $orderId]);
        }

        return ['success' => true, 'redirect' => $redirect];
    }

    public function expireDraft(string $token): bool
    {
        return DB::table('pending_bank_transfers')->where('token', trim($token))->delete() > 0;
    }

    public function cancelOrderDraft(string $token): bool
    {
        return $this->expireDraft($token);
    }
}
