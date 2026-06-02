<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
            $payload['gateway_response'] = is_string($gatewayResponse)
                ? $gatewayResponse
                : json_encode($gatewayResponse);
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
     *
     * @return array{payment_id:int, transaction_reference:string, reused:bool}
     */
    public function resolvePendingPaymentRecord(
        int $restaurantId,
        int $subscriptionId,
        int $planId,
        string $billingCycle,
        string $gateway,
        float $amount,
    ): array {
        $cycle = $billingCycle === 'annual' ? 'annual' : 'monthly';
        $existing = $this->findLatestPendingMatch($restaurantId, $subscriptionId, $planId, $cycle);

        if ($existing !== null && ! $this->isPendingRecent($existing)) {
            $this->markFailed((int) $existing->id, ['reason' => 'superseded_by_new_checkout']);
            $existing = null;
        }

        $reference = ($gateway === 'flutterwave' ? 'FLW_' : 'PS_').time().'_'.strtolower(substr(md5(uniqid('', true)), 0, 8));

        if ($existing !== null) {
            DB::table('payments')->where('id', $existing->id)->update([
                'payment_gateway' => $gateway,
                'transaction_reference' => $reference,
                'amount' => $amount,
                'currency' => 'NGN',
            ]);

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
