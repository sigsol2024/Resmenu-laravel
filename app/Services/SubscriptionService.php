<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Carbon;

class SubscriptionService
{
    public function getPlans(bool $activeOnly = true): array
    {
        $query = SubscriptionPlan::query()->orderBy('display_order');
        if ($activeOnly) {
            $query->where('is_active', 1);
        }

        return $query->get()->map(function (SubscriptionPlan $plan) {
            $row = $plan->toArray();
            if (is_string($row['features'] ?? null)) {
                $row['features'] = json_decode($row['features'], true) ?: [];
            }

            return $row;
        })->all();
    }

    public function syncExpiredStatuses(): int
    {
        $now = Carbon::now();
        $a = Subscription::query()
            ->where('status', 'active')
            ->whereNotNull('current_period_end')
            ->where('current_period_end', '<', $now)
            ->update(['status' => 'expired', 'updated_at' => $now]);

        $b = Subscription::query()
            ->where('status', 'trial')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', $now)
            ->update(['status' => 'expired', 'updated_at' => $now]);

        return $a + $b;
    }

    public function checkAccess(int $restaurantId): array
    {
        $subscription = Subscription::query()
            ->where('restaurant_id', $restaurantId)
            ->orderByDesc('created_at')
            ->first();

        if (! $subscription) {
            return [
                'valid' => false,
                'subscription' => null,
                'lockout_reason' => 'no_subscription',
                'message' => 'Please subscribe to a plan to access this feature.',
            ];
        }

        $sub = $subscription->toArray();

        if ($subscription->status === 'trial') {
            $trialEnd = $subscription->trial_ends_at ? Carbon::parse($subscription->trial_ends_at) : null;
            if ($trialEnd && $trialEnd->isFuture()) {
                return ['valid' => true, 'subscription' => $sub, 'lockout_reason' => null, 'message' => ''];
            }

            return [
                'valid' => false,
                'subscription' => $sub,
                'lockout_reason' => 'trial_expired',
                'message' => 'Your trial period has ended. Please subscribe to continue using the platform.',
            ];
        }

        if ($subscription->status === 'active') {
            $periodEnd = $subscription->current_period_end ? Carbon::parse($subscription->current_period_end) : null;
            if ($periodEnd && $periodEnd->isPast()) {
                return [
                    'valid' => false,
                    'subscription' => $sub,
                    'lockout_reason' => 'subscription_expired',
                    'message' => 'Your subscription has expired. Please renew to continue using the platform.',
                ];
            }

            return ['valid' => true, 'subscription' => $sub, 'lockout_reason' => null, 'message' => ''];
        }

        if ($subscription->status === 'expired') {
            return [
                'valid' => false,
                'subscription' => $sub,
                'lockout_reason' => 'subscription_expired',
                'message' => 'Your subscription has expired. Please renew to continue using the platform.',
            ];
        }

        return [
            'valid' => false,
            'subscription' => $sub,
            'lockout_reason' => 'unknown',
            'message' => 'There was an issue with your subscription. Please contact support.',
        ];
    }

    public function activateSubscription(int $subscriptionId, string $billingCycle = 'monthly'): bool
    {
        $cycle = $billingCycle === 'annual' ? 'annual' : 'monthly';
        $periodEnd = $cycle === 'annual' ? now()->addYear() : now()->addMonth();

        return Subscription::query()->where('id', $subscriptionId)->update([
            'status' => 'active',
            'billing_cycle' => $cycle,
            'current_period_start' => now(),
            'current_period_end' => $periodEnd,
            'trial_ends_at' => null,
            'cancelled_at' => null,
            'updated_at' => now(),
        ]) > 0;
    }

    public function deactivateSubscription(int $subscriptionId): bool
    {
        return Subscription::query()->where('id', $subscriptionId)->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'updated_at' => now(),
        ]) > 0;
    }
}
