<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SubscriptionPaymentLifecycleService
{
    public function pendingThresholdHours(): int
    {
        return max(1, (int) config('resmenu.subscription_payment_pending_hours', 6));
    }

    public function pendingCutoff(?int $hours = null): Carbon
    {
        return now()->subHours($hours ?? $this->pendingThresholdHours());
    }

    /** @param  array<string, mixed>|object  $payment */
    public function isPendingRecent(array|object $payment): bool
    {
        $status = is_array($payment) ? ($payment['status'] ?? '') : ($payment->status ?? '');

        if ($status !== 'pending') {
            return false;
        }

        $createdAt = is_array($payment) ? ($payment['created_at'] ?? null) : ($payment->created_at ?? null);
        if ($createdAt === null || $createdAt === '') {
            return false;
        }

        return Carbon::parse($createdAt)->gte($this->pendingCutoff());
    }

    public function markFailed(int $paymentId, mixed $gatewayResponse = null): bool
    {
        $payment = DB::table('payments')->where('id', $paymentId)->first();
        if (! $payment || ($payment->status ?? '') !== 'pending') {
            return false;
        }

        $payload = ['status' => 'failed'];
        if ($gatewayResponse !== null) {
            $payload['gateway_response'] = $this->mergeGatewayPayloadPreservingIntent(
                $payment->gateway_response ?? null,
                is_string($gatewayResponse) ? ['raw' => $gatewayResponse] : (array) $gatewayResponse,
            );
        }

        return DB::table('payments')->where('id', $paymentId)->update($payload) > 0;
    }

    public function markFailedByReference(string $reference, mixed $gatewayResponse = null): bool
    {
        if ($reference === '') {
            return false;
        }

        $payment = DB::table('payments')
            ->where('transaction_reference', $reference)
            ->where('status', 'pending')
            ->first();

        if (! $payment) {
            return false;
        }

        return $this->markFailed((int) $payment->id, $gatewayResponse);
    }

    public function failStalePendingSubscriptionPayments(?int $hours = null): int
    {
        $cutoff = $this->pendingCutoff($hours);

        return DB::table('payments')
            ->where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->update([
                'status' => 'failed',
                'gateway_response' => json_encode([
                    'reason' => 'stale_pending_timeout',
                    'failed_at' => now()->toIso8601String(),
                    'threshold_hours' => $hours ?? $this->pendingThresholdHours(),
                ]),
            ]);
    }

    /**
     * Reuse a recent pending payment or create a new row (marking stale matches as failed).
     * Serializes on the subscription row to prevent duplicate concurrent checkouts.
     *
     * @param  array<string, mixed>|null  $billingIntent
     * @return array{payment_id:int, transaction_reference:string, reused:bool}
     */
    public function resolvePendingPaymentRecord(
        int $restaurantId,
        int $subscriptionId,
        int $planId,
        string $billingCycle,
        string $gateway,
        float $amount,
        ?array $billingIntent = null,
    ): array {
        $cycle = $billingCycle === 'annual' ? 'annual' : 'monthly';

        return DB::transaction(function () use ($restaurantId, $subscriptionId, $planId, $cycle, $gateway, $amount, $billingIntent) {
            $subscription = DB::table('subscriptions')
                ->where('id', $subscriptionId)
                ->where('restaurant_id', $restaurantId)
                ->lockForUpdate()
                ->first();

            if (! $subscription) {
                throw new \RuntimeException('Subscription not found for pending payment resolution.');
            }

            $existing = $this->findLatestPendingMatch($restaurantId, $subscriptionId, $planId, $cycle);

            if ($existing !== null && ! $this->isPendingRecent($existing)) {
                $this->markFailed((int) $existing->id, ['reason' => 'superseded_by_new_checkout']);
                $existing = null;
            }

            // Re-check after possible markFailed (still under subscription lock).
            if ($existing === null) {
                $existing = $this->findLatestPendingMatch($restaurantId, $subscriptionId, $planId, $cycle);
                if ($existing !== null && ! $this->isPendingRecent($existing)) {
                    $this->markFailed((int) $existing->id, ['reason' => 'superseded_by_new_checkout']);
                    $existing = null;
                }
            }

            $reference = ($gateway === 'flutterwave' ? 'FLW_' : 'PS_').time().'_'.strtolower(substr(md5(uniqid('', true)), 0, 8));
            $intentPayload = $billingIntent ?? $this->buildBillingIntent(
                $restaurantId,
                $subscriptionId,
                $planId,
                $cycle,
                $amount,
                null,
                null,
            );

            if ($existing !== null) {
                $update = [
                    'payment_gateway' => $gateway,
                    'transaction_reference' => $reference,
                    'amount' => $amount,
                    'currency' => 'NGN',
                    'gateway_response' => $this->encodeBillingIntentResponse(
                        $existing->gateway_response ?? null,
                        $intentPayload,
                    ),
                ];
                if (Schema::hasColumn('payments', 'plan_id')) {
                    $update['plan_id'] = $planId;
                }
                if (Schema::hasColumn('payments', 'billing_cycle')) {
                    $update['billing_cycle'] = $cycle;
                }

                DB::table('payments')->where('id', $existing->id)->update($update);

                return [
                    'payment_id' => (int) $existing->id,
                    'transaction_reference' => $reference,
                    'reused' => true,
                ];
            }

            $insert = [
                'restaurant_id' => $restaurantId,
                'subscription_id' => $subscriptionId,
                'amount' => $amount,
                'currency' => 'NGN',
                'payment_gateway' => $gateway,
                'transaction_reference' => $reference,
                'status' => 'pending',
                'gateway_response' => $this->encodeBillingIntentResponse(null, $intentPayload),
                'created_at' => now(),
            ];

            if (Schema::hasColumn('payments', 'plan_id')) {
                $insert['plan_id'] = $planId;
            }
            if (Schema::hasColumn('payments', 'billing_cycle')) {
                $insert['billing_cycle'] = $cycle;
            }

            $paymentId = (int) DB::table('payments')->insertGetId($insert);

            return [
                'payment_id' => $paymentId,
                'transaction_reference' => $reference,
                'reused' => false,
            ];
        });
    }

    /**
     * @param  array<string, mixed>|null  $pricing
     * @return array<string, mixed>
     */
    public function buildBillingIntent(
        int $restaurantId,
        int $subscriptionId,
        int $planId,
        string $billingCycle,
        float $amount,
        ?string $pricingMode,
        ?string $applyMode,
    ): array {
        return [
            'restaurant_id' => $restaurantId,
            'subscription_id' => $subscriptionId,
            'plan_id' => $planId,
            'billing_cycle' => $billingCycle === 'annual' ? 'annual' : 'monthly',
            'amount' => round($amount, 2),
            'pricing_mode' => $pricingMode,
            'apply_mode' => $applyMode,
            'quoted_at' => now()->toIso8601String(),
        ];
    }

    /** @param  array<string, mixed>|object  $payment */
    public function extractBillingIntent(array|object $payment): ?array
    {
        $raw = is_array($payment) ? ($payment['gateway_response'] ?? null) : ($payment->gateway_response ?? null);
        if (! is_string($raw) || $raw === '') {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded)) {
            return null;
        }

        $intent = $decoded['billing_intent'] ?? null;

        return is_array($intent) ? $intent : null;
    }

    /**
     * @param  array<string, mixed>  $intent
     */
    public function encodeBillingIntentResponse(?string $existingJson, array $intent): string
    {
        $existing = [];
        if (is_string($existingJson) && $existingJson !== '') {
            $decoded = json_decode($existingJson, true);
            if (is_array($decoded)) {
                $existing = $decoded;
            }
        }

        $existing['billing_intent'] = $intent;

        return json_encode($existing) ?: json_encode(['billing_intent' => $intent]);
    }

    /**
     * @param  array<string, mixed>  $gatewayData
     */
    public function mergeGatewayPayloadPreservingIntent(?string $existingJson, array $gatewayData): string
    {
        $intent = null;
        if (is_string($existingJson) && $existingJson !== '') {
            $decoded = json_decode($existingJson, true);
            if (is_array($decoded) && isset($decoded['billing_intent']) && is_array($decoded['billing_intent'])) {
                $intent = $decoded['billing_intent'];
            }
        }

        $payload = $gatewayData;
        if ($intent !== null) {
            $payload['billing_intent'] = $intent;
        }

        return json_encode($payload) ?: '{}';
    }

    /**
     * @param  array<string, mixed>  $quote
     * @param  array<string, mixed>  $intent
     */
    public function isLiveQuoteCompatibleWithIntent(array $quote, array $intent, float $paidAmount): bool
    {
        if (($quote['outcome'] ?? '') !== 'charge') {
            return false;
        }

        if ((int) ($quote['restaurant_id'] ?? 0) !== (int) ($intent['restaurant_id'] ?? 0)) {
            return false;
        }
        if ((int) ($quote['target_plan_id'] ?? 0) !== (int) ($intent['plan_id'] ?? 0)) {
            return false;
        }

        $intentCycle = (($intent['billing_cycle'] ?? 'monthly') === 'annual') ? 'annual' : 'monthly';
        $quoteCycle = (($quote['target_billing_cycle'] ?? 'monthly') === 'annual') ? 'annual' : 'monthly';
        if ($intentCycle !== $quoteCycle) {
            return false;
        }

        $intentPricing = $intent['pricing_mode'] ?? null;
        $intentApply = $intent['apply_mode'] ?? null;
        $livePricing = $quote['pricing_mode'] ?? null;
        $liveApply = $quote['apply_mode'] ?? null;

        if ($intentPricing !== null && $livePricing !== null && $intentPricing !== $livePricing) {
            return false;
        }
        if ($intentApply !== null && $liveApply !== null && $intentApply !== $liveApply) {
            return false;
        }

        // Residual payment must never be fulfilled as a full activation.
        if (
            ($intentPricing === 'residual_difference' || $intentApply === 'preserve_period')
            && ($liveApply === 'activate' || $livePricing === 'full')
        ) {
            return false;
        }

        if (abs((float) ($quote['amount'] ?? 0) - $paidAmount) > 0.01) {
            return false;
        }
        if (isset($intent['amount']) && abs((float) $intent['amount'] - $paidAmount) > 0.01) {
            return false;
        }

        return true;
    }

    public function logPaymentReconciliation(string $reason, object $payment, ?array $quote = null, ?array $intent = null): void
    {
        Log::warning('Subscription payment requires reconciliation', [
            'reason' => $reason,
            'payment_id' => (int) ($payment->id ?? 0),
            'restaurant_id' => (int) ($payment->restaurant_id ?? 0),
            'subscription_id' => (int) ($payment->subscription_id ?? 0),
            'amount' => $payment->amount ?? null,
            'plan_id' => $payment->plan_id ?? null,
            'billing_cycle' => $payment->billing_cycle ?? null,
            'intent' => $intent,
            'live_outcome' => $quote['outcome'] ?? null,
            'live_pricing_mode' => $quote['pricing_mode'] ?? null,
            'live_apply_mode' => $quote['apply_mode'] ?? null,
            'live_amount' => $quote['amount'] ?? null,
        ]);
    }

    /** @return list<array<string, mixed>> */
    public function paymentHistoryForDisplay(int $restaurantId, int $limit = 10): array
    {
        $query = DB::table('payments as p')
            ->leftJoin('subscriptions as s', 's.id', '=', 'p.subscription_id');

        if (Schema::hasColumn('payments', 'plan_id')) {
            $query->leftJoin('subscription_plans as sp', 'sp.id', '=', 'p.plan_id');
            $planNameSelect = 'sp.name as plan_name';
        } else {
            $query->leftJoin('subscription_plans as sp', 'sp.id', '=', 's.plan_id');
            $planNameSelect = 'sp.name as plan_name';
        }

        $rows = $query
            ->where('p.restaurant_id', $restaurantId)
            ->orderByDesc('p.created_at')
            ->limit($limit)
            ->select(['p.*', DB::raw($planNameSelect)])
            ->get();

        return $rows->map(function ($row) {
            $payment = (array) $row;
            $payment['display'] = $this->displayStatusForPayment($payment);

            return $payment;
        })->all();
    }

    /** @param  array<string, mixed>  $payment */
    /** @return array{status:string, label:string, css_class:string, is_stale:bool} */
    public function displayStatusForPayment(array $payment): array
    {
        $rawStatus = (string) ($payment['status'] ?? 'pending');

        if ($rawStatus === 'pending' && ! $this->isPendingRecent($payment)) {
            return [
                'status' => 'failed',
                'label' => 'Failed',
                'css_class' => 'failed',
                'is_stale' => true,
            ];
        }

        return match ($rawStatus) {
            'success' => [
                'status' => 'success',
                'label' => 'Success',
                'css_class' => 'success',
                'is_stale' => false,
            ],
            'failed' => [
                'status' => 'failed',
                'label' => 'Failed',
                'css_class' => 'failed',
                'is_stale' => false,
            ],
            'refunded' => [
                'status' => 'refunded',
                'label' => 'Refunded',
                'css_class' => 'failed',
                'is_stale' => false,
            ],
            default => [
                'status' => 'pending',
                'label' => 'Pending',
                'css_class' => 'pending',
                'is_stale' => false,
            ],
        };
    }

    private function findLatestPendingMatch(
        int $restaurantId,
        int $subscriptionId,
        int $planId,
        string $billingCycle,
    ): ?object {
        $query = DB::table('payments')
            ->where('restaurant_id', $restaurantId)
            ->where('subscription_id', $subscriptionId)
            ->where('status', 'pending')
            ->orderByDesc('id');

        if (Schema::hasColumn('payments', 'plan_id')) {
            $query->where('plan_id', $planId);
        }
        if (Schema::hasColumn('payments', 'billing_cycle')) {
            $query->where('billing_cycle', $billingCycle);
        }

        return $query->first();
    }
}
