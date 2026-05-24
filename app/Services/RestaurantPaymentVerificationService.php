<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RestaurantPaymentVerificationService
{
    public function __construct(private PaymentGatewayService $gateway) {}

    /**
     * @return array{
     *   ok: bool,
     *   already_fulfilled?: bool,
     *   order_id?: int,
     *   slug?: string,
     *   type?: string,
     *   error?: string,
     *   restaurant_id?: int
     * }
     */
    public function verifyCallbackPayment(string $reference, string $gateway, ?string $transactionId = null): array
    {
        $gateway = strtolower($gateway);
        if (! in_array($gateway, ['paystack', 'flutterwave'], true)) {
            return ['ok' => false, 'error' => 'unsupported_gateway'];
        }

        $replayKey = 'payment_callback_attempt:'.$gateway.':'.$reference;
        if (! Cache::add($replayKey, 1, now()->addMinutes(10))) {
            Log::warning('Payment callback replay throttled', ['reference' => $reference, 'gateway' => $gateway]);

            return ['ok' => false, 'error' => 'replay_throttled'];
        }

        $draft = DB::table('pending_online_payments')
            ->where('reference', $reference)
            ->where('gateway', $gateway)
            ->first();

        if (! $draft) {
            $cached = $this->cachedFulfillment($gateway, $reference);
            if ($cached !== null) {
                return ['ok' => true, 'already_fulfilled' => true] + $cached;
            }

            return ['ok' => false, 'error' => 'unknown_reference'];
        }

        $restaurantId = (int) $draft->restaurant_id;
        $expectedTotal = (float) $draft->total;

        $verified = $gateway === 'paystack'
            ? $this->verifyPaystack($restaurantId, $reference, $expectedTotal)
            : $this->verifyFlutterwave($restaurantId, $reference, $transactionId, $expectedTotal);

        if (! $verified['ok']) {
            Log::warning('Payment callback verification failed', [
                'reference' => $reference,
                'gateway' => $gateway,
                'reason' => $verified['error'] ?? 'unknown',
            ]);

            return $verified;
        }

        Cache::put('payment_callback_verified:'.$gateway.':'.$reference, 1, now()->addDays(1));

        return [
            'ok' => true,
            'restaurant_id' => $restaurantId,
            'type' => ($draft->payment_type ?? 'order') === 'reservation' ? 'reservation' : 'order',
        ];
    }

    /** @return array{ok:bool, error?:string} */
    private function verifyPaystack(int $restaurantId, string $reference, float $expectedTotal): array
    {
        $keys = $this->gateway->restaurantKeys($restaurantId, 'paystack');
        if ($keys['secret_key'] === '') {
            return ['ok' => false, 'error' => 'gateway_not_configured'];
        }

        $response = Http::withToken($keys['secret_key'])
            ->timeout(15)
            ->get('https://api.paystack.co/transaction/verify/'.urlencode($reference));

        if (! $response->successful()) {
            return ['ok' => false, 'error' => 'verify_http_failed'];
        }

        $data = $response->json('data', []);
        if (($data['status'] ?? '') !== 'success') {
            return ['ok' => false, 'error' => 'payment_not_successful'];
        }

        if (strtoupper((string) ($data['currency'] ?? 'NGN')) !== 'NGN') {
            return ['ok' => false, 'error' => 'currency_mismatch'];
        }

        $paidKobo = (int) ($data['amount'] ?? 0);
        $expectedKobo = (int) round($expectedTotal * 100);
        if ($paidKobo !== $expectedKobo) {
            Log::warning('Paystack amount mismatch', [
                'reference' => $reference,
                'expected_kobo' => $expectedKobo,
                'paid_kobo' => $paidKobo,
            ]);

            return ['ok' => false, 'error' => 'amount_mismatch'];
        }

        $metaRef = (string) ($data['metadata']['reference'] ?? $data['reference'] ?? '');
        if ($metaRef !== '' && $metaRef !== $reference) {
            return ['ok' => false, 'error' => 'reference_mismatch'];
        }

        return ['ok' => true];
    }

    /** @return array{ok:bool, error?:string} */
    private function verifyFlutterwave(
        int $restaurantId,
        string $reference,
        ?string $transactionId,
        float $expectedTotal,
    ): array {
        $keys = $this->gateway->restaurantKeys($restaurantId, 'flutterwave');
        if ($keys['secret_key'] === '') {
            return ['ok' => false, 'error' => 'gateway_not_configured'];
        }

        $txId = $transactionId;
        if ($txId === null || $txId === '') {
            return ['ok' => false, 'error' => 'missing_transaction_id'];
        }

        $response = Http::withToken($keys['secret_key'])
            ->timeout(15)
            ->get('https://api.flutterwave.com/v3/transactions/'.urlencode($txId).'/verify');

        if (! $response->successful()) {
            return ['ok' => false, 'error' => 'verify_http_failed'];
        }

        $data = $response->json('data', []);
        if (($data['status'] ?? '') !== 'successful') {
            return ['ok' => false, 'error' => 'payment_not_successful'];
        }

        if (strtoupper((string) ($data['currency'] ?? 'NGN')) !== 'NGN') {
            return ['ok' => false, 'error' => 'currency_mismatch'];
        }

        $paid = (float) ($data['amount'] ?? 0);
        if (abs($paid - $expectedTotal) > 0.01) {
            Log::warning('Flutterwave amount mismatch', [
                'reference' => $reference,
                'expected' => $expectedTotal,
                'paid' => $paid,
            ]);

            return ['ok' => false, 'error' => 'amount_mismatch'];
        }

        $txRef = (string) ($data['tx_ref'] ?? '');
        if ($txRef !== '' && $txRef !== $reference) {
            return ['ok' => false, 'error' => 'reference_mismatch'];
        }

        return ['ok' => true];
    }

    /** @return array{order_id?:int, slug?:string, type?:string}|null */
    private function cachedFulfillment(string $gateway, string $reference): ?array
    {
        $payload = Cache::get('fulfilled_order_ref:'.$gateway.':'.$reference);
        if (! is_array($payload)) {
            return null;
        }

        return $payload;
    }
}
