<?php

namespace App\Services;

use App\Models\Subscription;
use App\Support\LegacyEncryption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentGatewayService
{
    public function __construct(private SubscriptionService $subscriptions) {}

    /** @return array{redirect_url?:string, reference?:string, error?:string} */
    public function initializeSubscriptionPayment(
        int $restaurantId,
        int $planId,
        string $billingCycle = 'monthly',
        string $gateway = 'paystack',
    ): array {
        $cycle = $billingCycle === 'annual' ? 'annual' : 'monthly';
        $plan = DB::table('subscription_plans')->where('id', $planId)->where('is_active', 1)->first();
        if (! $plan) {
            return ['error' => 'Plan not found'];
        }

        $restaurant = DB::table('restaurants')->where('id', $restaurantId)->first();
        $managerEmail = DB::table('managers')->where('restaurant_id', $restaurantId)->value('email');
        if (! $restaurant || ! $managerEmail) {
            return ['error' => 'Restaurant or manager email missing'];
        }

        $subscription = Subscription::query()->where('restaurant_id', $restaurantId)->orderByDesc('id')->first();
        if (! $subscription) {
            $subscription = Subscription::forceCreate([
                'restaurant_id' => $restaurantId,
                'plan_id' => $planId,
                'billing_cycle' => $cycle,
                'status' => 'pending',
            ]);
        }

        $amount = $cycle === 'annual' ? (float) ($plan->annual_price ?? 0) : (float) ($plan->monthly_price ?? 0);
        $reference = ($gateway === 'flutterwave' ? 'FLW_' : 'PS_').time().'_'.Str::lower(Str::random(8));

        $paymentId = DB::table('payments')->insertGetId([
            'restaurant_id' => $restaurantId,
            'subscription_id' => $subscription->id,
            'plan_id' => $planId,
            'billing_cycle' => $cycle,
            'amount' => $amount,
            'currency' => 'NGN',
            'payment_gateway' => $gateway,
            'transaction_reference' => $reference,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $metadata = [
            'payment_id' => $paymentId,
            'subscription_id' => $subscription->id,
            'restaurant_id' => $restaurantId,
            'plan_id' => $planId,
            'billing_cycle' => $cycle,
        ];

        $keys = $this->platformKeys($gateway);
        if (empty($keys['secret_key'])) {
            return ['error' => 'Payment gateway not configured'];
        }

        if ($gateway === 'flutterwave') {
            return $this->flutterwaveInitialize(
                $keys['secret_key'],
                $amount,
                $managerEmail,
                $restaurant->name ?? 'Restaurant',
                $reference,
                $metadata,
                route('manager.billing.payment-callback', ['gateway' => 'flutterwave']),
            );
        }

        return $this->paystackInitialize(
            $keys['secret_key'],
            (int) round($amount * 100),
            $managerEmail,
            $reference,
            $metadata,
            route('manager.billing.payment-callback', ['gateway' => 'paystack']),
        );
    }

    /** @return array{redirect_url?:string, reference?:string, error?:string} */
    public function initializeRestaurantPayment(
        int $restaurantId,
        string $gateway,
        float $amount,
        string $email,
        string $reference,
        array $metadata,
        string $slug,
    ): array {
        $keys = $this->restaurantKeys($restaurantId, $gateway);
        if (empty($keys['secret_key'])) {
            return ['error' => 'Gateway not configured for this restaurant'];
        }

        $callback = route('public.order.payment-callback', ['gateway' => $gateway, 'slug' => $slug]);
        $metadata['reference'] = $reference;
        $metadata['restaurant_id'] = $restaurantId;
        $metadata['slug'] = $slug;

        if ($gateway === 'flutterwave') {
            return $this->flutterwaveInitialize(
                $keys['secret_key'],
                $amount,
                $email,
                $metadata['customer_name'] ?? 'Customer',
                $reference,
                $metadata,
                $callback,
            );
        }

        return $this->paystackInitialize(
            $keys['secret_key'],
            (int) round($amount * 100),
            $email,
            $reference,
            $metadata,
            $callback,
        );
    }

    public function processPlatformPaystackSuccess(array $data): bool
    {
        $reference = (string) ($data['reference'] ?? '');
        if ($reference === '') {
            return false;
        }

        return DB::transaction(function () use ($data, $reference) {
            $payment = DB::table('payments')
                ->where('transaction_reference', $reference)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                return false;
            }

            if ($payment->status === 'success') {
                return true;
            }

            if (! $this->verifyPlatformPayment($data, $payment, 'paystack')) {
                return false;
            }

            DB::table('payments')->where('id', $payment->id)->update([
                'status' => 'success',
                'paid_at' => now(),
                'gateway_response' => json_encode($data),
            ]);

            return $this->activateSubscriptionForVerifiedPayment($payment);
        });
    }

    public function processPlatformFlutterwaveSuccess(array $data): bool
    {
        $reference = (string) ($data['tx_ref'] ?? $data['reference'] ?? '');
        if ($reference === '') {
            return false;
        }

        return DB::transaction(function () use ($data, $reference) {
            $payment = DB::table('payments')
                ->where('transaction_reference', $reference)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                return false;
            }

            if ($payment->status === 'success') {
                return true;
            }

            if (! $this->verifyPlatformPayment($data, $payment, 'flutterwave')) {
                return false;
            }

            DB::table('payments')->where('id', $payment->id)->update([
                'status' => 'success',
                'paid_at' => now(),
                'gateway_response' => json_encode($data),
            ]);

            return $this->activateSubscriptionForVerifiedPayment($payment);
        });
    }

    /** @param  array<string, mixed>  $gatewayData */
    public function verifyPlatformPayment(array $gatewayData, object $payment, string $gateway): bool
    {
        $gateway = strtolower($gateway);
        $reference = (string) ($payment->transaction_reference ?? '');

        if ($gateway === 'paystack') {
            $dataRef = (string) ($gatewayData['reference'] ?? '');
            if ($dataRef !== '' && $dataRef !== $reference) {
                Log::warning('Platform Paystack reference mismatch', ['expected' => $reference, 'got' => $dataRef]);

                return false;
            }

            if (strtoupper((string) ($gatewayData['currency'] ?? 'NGN')) !== 'NGN') {
                Log::warning('Platform Paystack currency mismatch', ['reference' => $reference]);

                return false;
            }

            $paidKobo = (int) ($gatewayData['amount'] ?? 0);
            $expectedKobo = (int) round((float) $payment->amount * 100);
            if ($paidKobo !== $expectedKobo) {
                Log::warning('Platform Paystack amount mismatch', [
                    'reference' => $reference,
                    'expected_kobo' => $expectedKobo,
                    'paid_kobo' => $paidKobo,
                ]);

                return false;
            }
        } elseif ($gateway === 'flutterwave') {
            $dataRef = (string) ($gatewayData['tx_ref'] ?? $gatewayData['reference'] ?? '');
            if ($dataRef !== '' && $dataRef !== $reference) {
                Log::warning('Platform Flutterwave reference mismatch', ['expected' => $reference, 'got' => $dataRef]);

                return false;
            }

            if (strtoupper((string) ($gatewayData['currency'] ?? 'NGN')) !== 'NGN') {
                Log::warning('Platform Flutterwave currency mismatch', ['reference' => $reference]);

                return false;
            }

            $paid = (float) ($gatewayData['amount'] ?? 0);
            $expected = (float) $payment->amount;
            if (abs($paid - $expected) > 0.01) {
                Log::warning('Platform Flutterwave amount mismatch', [
                    'reference' => $reference,
                    'expected' => $expected,
                    'paid' => $paid,
                ]);

                return false;
            }
        } else {
            return false;
        }

        $expectedPlanId = (int) ($payment->plan_id ?? 0);
        if ($expectedPlanId <= 0) {
            $expectedPlanId = (int) DB::table('subscriptions')
                ->where('id', $payment->subscription_id)
                ->value('plan_id');
        }

        $meta = $gateway === 'paystack'
            ? ($gatewayData['metadata'] ?? [])
            : ($gatewayData['meta'] ?? []);
        $metaPlanId = (int) ($meta['plan_id'] ?? 0);
        if ($expectedPlanId > 0 && $metaPlanId > 0 && $metaPlanId !== $expectedPlanId) {
            Log::warning('Platform payment plan_id tamper rejected', [
                'reference' => $reference,
                'expected_plan_id' => $expectedPlanId,
                'metadata_plan_id' => $metaPlanId,
            ]);

            return false;
        }

        return true;
    }

    private function activateSubscriptionForVerifiedPayment(object $payment): bool
    {
        $subscriptionId = (int) $payment->subscription_id;
        $planId = (int) ($payment->plan_id ?? 0);
        $cycle = ($payment->billing_cycle ?? 'monthly') === 'annual' ? 'annual' : 'monthly';

        if ($planId > 0) {
            DB::table('subscriptions')->where('id', $subscriptionId)->update([
                'plan_id' => $planId,
                'billing_cycle' => $cycle,
                'updated_at' => now(),
            ]);
        }

        return $this->subscriptions->activateSubscription($subscriptionId, $cycle);
    }

    public function verifyPaystackSignature(string $payload, string $signature, ?string $secret = null): bool
    {
        $secret = $secret ?? $this->platformKeys('paystack')['secret_key'];
        if (! $secret || $signature === '') {
            return false;
        }

        return hash_equals(hash_hmac('sha512', $payload, $secret), $signature);
    }

    public function verifyRestaurantPaystackSignature(string $payload, string $signature, int $restaurantId): bool
    {
        $secret = $this->restaurantKeys($restaurantId, 'paystack')['secret_key'];

        return $this->verifyPaystackSignature($payload, $signature, $secret ?: null);
    }

    public function verifyFlutterwaveSignature(string $signature, ?string $secret = null): bool
    {
        $secret = $secret ?? $this->platformKeys('flutterwave')['secret_key'];
        if (! $secret || $signature === '') {
            return false;
        }

        return hash_equals($secret, $signature);
    }

    public function verifyRestaurantFlutterwaveSignature(string $signature, int $restaurantId): bool
    {
        $secret = $this->restaurantKeys($restaurantId, 'flutterwave')['secret_key'];

        return $this->verifyFlutterwaveSignature($signature, $secret ?: null);
    }

    /** @return array{public_key:string, secret_key:string} */
    public function platformKeys(string $gateway): array
    {
        $row = DB::table('payment_settings')->where('gateway', $gateway)->where('is_active', 1)->first();
        if (! $row) {
            return ['public_key' => '', 'secret_key' => ''];
        }

        $test = (bool) ($row->test_mode ?? true);

        return [
            'public_key' => $test ? ($row->public_key_test ?? '') : ($row->public_key_live ?? ''),
            'secret_key' => LegacyEncryption::decrypt($test ? ($row->secret_key_test ?? '') : ($row->secret_key_live ?? '')),
        ];
    }

    /** @return array{public_key:string, secret_key:string} */
    public function restaurantKeys(int $restaurantId, string $gateway): array
    {
        $row = DB::table('restaurant_payment_settings')
            ->where('restaurant_id', $restaurantId)
            ->where('gateway', $gateway)
            ->where('is_active', 1)
            ->first();

        if (! $row) {
            return ['public_key' => '', 'secret_key' => ''];
        }

        $test = (bool) ($row->test_mode ?? true);

        return [
            'public_key' => $test ? ($row->public_key_test ?? '') : ($row->public_key_live ?? ''),
            'secret_key' => LegacyEncryption::decrypt($test ? ($row->secret_key_test ?? '') : ($row->secret_key_live ?? '')),
        ];
    }

    /** @param  array<string, mixed>  $metadata */
    private function paystackInitialize(
        string $secret,
        int $amountKobo,
        string $email,
        string $reference,
        array $metadata,
        string $callbackUrl,
    ): array {
        $response = Http::withToken($secret)->post('https://api.paystack.co/transaction/initialize', [
            'email' => $email,
            'amount' => $amountKobo,
            'reference' => $reference,
            'callback_url' => $callbackUrl,
            'metadata' => $metadata,
        ]);

        if (! $response->successful()) {
            Log::warning('Paystack init failed', ['status' => $response->status()]);

            return ['error' => 'Failed to initialize payment'];
        }

        $body = $response->json('data') ?? [];

        return [
            'redirect_url' => $body['authorization_url'] ?? null,
            'reference' => $body['reference'] ?? $reference,
        ];
    }

    /** @param  array<string, mixed>  $metadata */
    private function flutterwaveInitialize(
        string $secret,
        float $amount,
        string $email,
        string $name,
        string $reference,
        array $metadata,
        string $callbackUrl,
    ): array {
        $response = Http::withToken($secret)->post('https://api.flutterwave.com/v3/payments', [
            'tx_ref' => $reference,
            'amount' => $amount,
            'currency' => 'NGN',
            'redirect_url' => $callbackUrl,
            'customer' => ['email' => $email, 'name' => $name],
            'meta' => $metadata,
        ]);

        if (! $response->successful()) {
            return ['error' => 'Failed to initialize payment'];
        }

        return [
            'redirect_url' => $response->json('data.link'),
            'reference' => $reference,
        ];
    }

    /** @param array<string, mixed> $gatewayResponse */
    public function updatePaymentStatus(int $paymentId, string $status, ?array $gatewayResponse = null): bool
    {
        $valid = ['pending', 'success', 'failed', 'refunded'];
        if (! in_array($status, $valid, true)) {
            return false;
        }

        $payload = ['status' => $status];
        if ($gatewayResponse !== null) {
            $payload['gateway_response'] = json_encode($gatewayResponse);
        }
        if ($status === 'success') {
            $payload['paid_at'] = now();
        }

        return DB::table('payments')->where('id', $paymentId)->update($payload) > 0;
    }

    /** @param array{restaurant_id:int,subscription_id:int,amount:float,payment_gateway:string,transaction_reference?:string,status?:string} $data */
    public function createPayment(array $data): ?int
    {
        return DB::table('payments')->insertGetId([
            'restaurant_id' => $data['restaurant_id'],
            'subscription_id' => $data['subscription_id'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'NGN',
            'payment_gateway' => $data['payment_gateway'],
            'transaction_reference' => $data['transaction_reference'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'created_at' => now(),
        ]) ?: null;
    }

    public function activateSubscriptionForPayment(int $paymentId): void
    {
        $payment = DB::table('payments')->where('id', $paymentId)->first();
        if (! $payment || ! $payment->subscription_id) {
            return;
        }

        $sub = DB::table('subscriptions')->where('id', $payment->subscription_id)->first();
        if (! $sub) {
            return;
        }

        $cycle = ($sub->billing_cycle ?? 'monthly') === 'annual' ? 'annual' : 'monthly';
        $this->subscriptions->activateSubscription((int) $payment->subscription_id, $cycle);
    }
}
