<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

    /** @return array<string, mixed>|null */
    public function getRestaurantSubscription(int $restaurantId): ?array
    {
        $row = Subscription::query()
            ->from('subscriptions as s')
            ->join('subscription_plans as p', 'p.id', '=', 's.plan_id')
            ->where('s.restaurant_id', $restaurantId)
            ->orderByDesc('s.created_at')
            ->select([
                's.*',
                'p.name as plan_name',
                'p.slug as plan_slug',
                'p.monthly_price',
                'p.annual_price',
                'p.display_order',
                'p.max_categories',
                'p.max_menu_items',
                'p.max_qr_styles',
                'p.max_templates',
                'p.features as plan_features',
            ])
            ->first();

        if (! $row) {
            return null;
        }

        $sub = $row->toArray();
        if (is_string($sub['plan_features'] ?? null)) {
            $sub['plan_features'] = json_decode($sub['plan_features'], true) ?: [];
        }

        return $sub;
    }

    public function isSubscriptionActive(int $restaurantId): bool
    {
        return $this->checkAccess($restaurantId)['valid'];
    }

    public function hasFeatureAccess(int $restaurantId, string $feature, ?int $currentCount = null): bool
    {
        $subscription = $this->getRestaurantSubscription($restaurantId);
        if (! $subscription || ! $this->isSubscriptionActive($restaurantId)) {
            return false;
        }

        $featureMap = [
            'categories' => 'max_categories',
            'menu_items' => 'max_menu_items',
            'qr_styles' => 'max_qr_styles',
            'templates' => 'max_templates',
        ];

        if (isset($featureMap[$feature])) {
            $max = $subscription[$featureMap[$feature]] ?? 0;
            if ((int) $max === -1) {
                return true;
            }
            if ($currentCount !== null) {
                return $currentCount < (int) $max;
            }

            return true;
        }

        $planFeatures = $subscription['plan_features'] ?? [];
        if (is_array($planFeatures) && array_key_exists($feature, $planFeatures)) {
            return (bool) $planFeatures[$feature];
        }

        $planSlug = strtolower((string) ($subscription['plan_slug'] ?? ''));
        if ($feature === 'food_ordering') {
            return in_array($planSlug, ['professional', 'enterprise'], true);
        }
        if ($feature === 'table_reservations') {
            return $planSlug === 'enterprise';
        }

        return false;
    }

    /** @return array{used:int, limit:int|string, remaining:int|string, unlimited:bool} */
    public function getRemainingUsage(int $restaurantId, string $feature): array
    {
        $result = ['used' => 0, 'limit' => 0, 'remaining' => 0, 'unlimited' => false];
        $subscription = $this->getRestaurantSubscription($restaurantId);
        if (! $subscription) {
            return $result;
        }

        $featureMap = [
            'categories' => 'max_categories',
            'menu_items' => 'max_menu_items',
            'qr_styles' => 'max_qr_styles',
            'templates' => 'max_templates',
        ];

        if (! isset($featureMap[$feature])) {
            return $result;
        }

        $maxAllowed = (int) ($subscription[$featureMap[$feature]] ?? 0);
        if ($maxAllowed === -1) {
            $result['unlimited'] = true;
            $result['limit'] = 'unlimited';
            $result['remaining'] = 'unlimited';
        } else {
            $result['limit'] = $maxAllowed;
        }

        switch ($feature) {
            case 'categories':
                $result['used'] = (int) DB::table('categories')->where('restaurant_id', $restaurantId)->count();
                break;
            case 'menu_items':
                $result['used'] = (int) DB::table('menu_items')->where('restaurant_id', $restaurantId)->count();
                break;
            case 'qr_styles':
                $result['used'] = (int) DB::table('restaurant_qr_codes')->where('restaurant_id', $restaurantId)->count();
                break;
            case 'templates':
                $result['used'] = 1;
                break;
        }

        if (! $result['unlimited']) {
            $result['remaining'] = max(0, $maxAllowed - $result['used']);
        }

        return $result;
    }

    public function canAddCategory(int $restaurantId): bool
    {
        $usage = $this->getRemainingUsage($restaurantId, 'categories');

        return $usage['unlimited'] || $usage['used'] < (int) $usage['limit'];
    }

    public function canAddMenuItem(int $restaurantId): bool
    {
        $usage = $this->getRemainingUsage($restaurantId, 'menu_items');

        return $usage['unlimited'] || $usage['used'] < (int) $usage['limit'];
    }

    /** @return array<string, array<string, mixed>> */
    public function getUsageSummary(int $restaurantId): array
    {
        return [
            'categories' => $this->getRemainingUsage($restaurantId, 'categories'),
            'menu_items' => $this->getRemainingUsage($restaurantId, 'menu_items'),
            'qr_styles' => $this->getRemainingUsage($restaurantId, 'qr_styles'),
            'templates' => $this->getRemainingUsage($restaurantId, 'templates'),
        ];
    }

    public function formatSubscriptionPrice(float|string $amount, string $currency = 'NGN'): string
    {
        $symbols = ['NGN' => '₦', 'USD' => '$', 'GBP' => '£', 'EUR' => '€'];
        $symbol = $symbols[$currency] ?? $currency.' ';

        return $symbol.number_format((float) $amount, 2);
    }

    /** @param  array<string, mixed>|null  $subscription */
    public function getTrialDaysRemaining(?array $subscription): int
    {
        if (! $subscription || ($subscription['status'] ?? '') !== 'trial' || empty($subscription['trial_ends_at'])) {
            return 0;
        }

        $trialEnd = Carbon::parse($subscription['trial_ends_at']);
        if ($trialEnd->isPast()) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($trialEnd) / 86400);
    }

    /** @param  array<string, mixed>|null  $subscription */
    public function getSubscriptionStatusInfo(?array $subscription): array
    {
        if (! $subscription) {
            return [
                'label' => 'No Subscription',
                'class' => 'badge-secondary',
                'description' => 'No active subscription',
                'effective_status' => 'none',
            ];
        }

        $status = $this->resolveEffectiveSubscriptionStatus($subscription);

        return match ($status) {
            'trial' => [
                'label' => 'Trial',
                'class' => 'badge-info',
                'description' => $this->getTrialDaysRemaining($subscription).' days remaining',
                'effective_status' => 'trial',
            ],
            'active' => [
                'label' => 'Active',
                'class' => 'badge-success',
                'description' => 'Renews on '.(
                    ! empty($subscription['current_period_end'])
                        ? Carbon::parse($subscription['current_period_end'])->format('M j, Y')
                        : 'N/A'
                ),
                'effective_status' => 'active',
            ],
            'expired' => [
                'label' => 'Expired',
                'class' => 'badge-danger',
                'description' => 'Please renew to continue',
                'effective_status' => 'expired',
            ],
            'cancelled' => [
                'label' => 'Cancelled',
                'class' => 'badge-warning',
                'description' => 'Subscription cancelled',
                'effective_status' => 'cancelled',
            ],
            default => [
                'label' => ucfirst((string) $status),
                'class' => 'badge-secondary',
                'description' => '',
                'effective_status' => $status,
            ],
        };
    }

    /** @param  array<string, mixed>  $subscription */
    private function resolveEffectiveSubscriptionStatus(array $subscription): string
    {
        $status = (string) ($subscription['status'] ?? '');

        if ($status === 'trial') {
            if (! empty($subscription['trial_ends_at']) && Carbon::parse($subscription['trial_ends_at'])->isFuture()) {
                return 'trial';
            }

            return 'expired';
        }

        if ($status === 'active') {
            if (! empty($subscription['current_period_end']) && Carbon::parse($subscription['current_period_end'])->isPast()) {
                return 'expired';
            }

            return 'active';
        }

        return $status;
    }

    /** @param  array<string, mixed>  $plan */
    public function getSubscriptionPlanRank(array $plan): int
    {
        return (int) ($plan['display_order'] ?? 0);
    }

    /**
     * @param  array<string, mixed>|null  $currentSubscription
     * @param  array<string, mixed>  $targetPlan
     * @return array{mode:string, reason:string, type:string}
     */
    public function getSubscriptionChangeDecision(?array $currentSubscription, array $targetPlan, string $targetBillingCycle): array
    {
        $targetCycle = $targetBillingCycle === 'annual' ? 'annual' : 'monthly';

        if (! $currentSubscription) {
            return ['mode' => 'immediate', 'reason' => 'new_subscription', 'type' => 'new'];
        }

        $currentPlanId = (int) ($currentSubscription['plan_id'] ?? 0);
        $targetPlanId = (int) ($targetPlan['id'] ?? 0);
        $currentCycle = ($currentSubscription['billing_cycle'] ?? 'monthly') === 'annual' ? 'annual' : 'monthly';
        $status = (string) ($currentSubscription['status'] ?? '');

        if ($currentPlanId === $targetPlanId && $currentCycle === $targetCycle) {
            return ['mode' => 'none', 'reason' => 'already_on_plan', 'type' => 'same'];
        }

        if (in_array($status, ['trial', 'pending', 'expired', 'cancelled'], true)) {
            return ['mode' => 'immediate', 'reason' => 'non_active_or_trial', 'type' => 'change'];
        }

        $currentRank = $this->getSubscriptionPlanRank($currentSubscription);
        $targetRank = $this->getSubscriptionPlanRank($targetPlan);

        if ($targetRank > $currentRank) {
            return ['mode' => 'immediate', 'reason' => 'upgrade', 'type' => 'upgrade'];
        }

        if ($targetRank < $currentRank) {
            return ['mode' => 'scheduled', 'reason' => 'downgrade', 'type' => 'downgrade'];
        }

        if ($currentCycle !== $targetCycle) {
            return ['mode' => 'scheduled', 'reason' => 'billing_cycle_change', 'type' => 'cycle_change'];
        }

        return ['mode' => 'none', 'reason' => 'already_on_plan', 'type' => 'same'];
    }

    /** @return array<string, mixed>|null */
    public function getScheduledSubscriptionChange(int $subscriptionId): ?array
    {
        $row = DB::table('subscription_change_requests as r')
            ->join('subscription_plans as p', 'p.id', '=', 'r.to_plan_id')
            ->where('r.subscription_id', $subscriptionId)
            ->where('r.status', 'pending')
            ->orderByDesc('r.id')
            ->select(['r.*', 'p.name as to_plan_name', 'p.slug as to_plan_slug'])
            ->first();

        return $row ? (array) $row : null;
    }

    public function createOrUpdateScheduledSubscriptionChange(
        int $restaurantId,
        int $subscriptionId,
        int $toPlanId,
        string $toBillingCycle,
        string $effectiveAt,
        string $changeType = 'cycle_change',
        string $requestedBy = 'manager',
    ): bool {
        $subscription = DB::table('subscriptions')->where('id', $subscriptionId)->first();
        if (! $subscription) {
            return false;
        }

        $cycle = $toBillingCycle === 'annual' ? 'annual' : 'monthly';
        $pending = DB::table('subscription_change_requests')
            ->where('subscription_id', $subscriptionId)
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->first();

        if ($pending) {
            return DB::table('subscription_change_requests')
                ->where('id', $pending->id)
                ->update([
                    'to_plan_id' => $toPlanId,
                    'to_billing_cycle' => $cycle,
                    'change_type' => $changeType,
                    'effective_at' => $effectiveAt,
                    'requested_by' => $requestedBy,
                    'updated_at' => now(),
                ]) > 0;
        }

        return DB::table('subscription_change_requests')->insert([
            'restaurant_id' => $restaurantId,
            'subscription_id' => $subscriptionId,
            'from_plan_id' => $subscription->plan_id,
            'to_plan_id' => $toPlanId,
            'from_billing_cycle' => $subscription->billing_cycle,
            'to_billing_cycle' => $cycle,
            'change_type' => $changeType,
            'effective_at' => $effectiveAt,
            'status' => 'pending',
            'requested_by' => $requestedBy,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function cancelScheduledSubscriptionChange(int $subscriptionId): bool
    {
        return DB::table('subscription_change_requests')
            ->where('subscription_id', $subscriptionId)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled', 'updated_at' => now()]) > 0;
    }

    /** @return list<array<string, mixed>> */
    public function getPaymentHistory(int $restaurantId, int $limit = 10): array
    {
        return DB::table('payments as p')
            ->leftJoin('subscriptions as s', 's.id', '=', 'p.subscription_id')
            ->leftJoin('subscription_plans as sp', 'sp.id', '=', 's.plan_id')
            ->where('p.restaurant_id', $restaurantId)
            ->orderByDesc('p.created_at')
            ->limit($limit)
            ->select(['p.*', 'sp.name as plan_name'])
            ->get()
            ->map(fn ($row) => (array) $row)
            ->all();
    }

    /** @param  array<string, mixed>  $plan */
    public function getPlanById(int $planId): ?array
    {
        $plan = SubscriptionPlan::query()->find($planId);

        return $plan ? $plan->toArray() : null;
    }
}
