<?php

namespace App\Services;

use App\Support\PlanVisibilityResult;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PlanVisibilityService
{
    private const CACHE_TTL_MINUTES = 10;

    public function __construct(private SubscriptionService $subscriptions) {}

    public function resolve(int $restaurantId, ?array $targetPlan = null): PlanVisibilityResult
    {
        if ($targetPlan !== null) {
            $planId = (int) ($targetPlan['id'] ?? 0);
            $cacheKey = "plan_visibility:{$restaurantId}:target:{$planId}";

            return Cache::remember(
                $cacheKey,
                now()->addMinutes(self::CACHE_TTL_MINUTES),
                fn () => $this->buildVisibility($restaurantId, $targetPlan),
            );
        }

        $subscription = $this->subscriptions->getRestaurantSubscription($restaurantId);
        $planId = (int) ($subscription['plan_id'] ?? 0);
        $plan = $subscription ? $this->planLimitsFromSubscription($subscription) : null;

        if ($plan === null) {
            return $this->buildVisibility($restaurantId, [
                'id' => 0,
                'max_categories' => -1,
                'max_menu_items' => -1,
            ]);
        }

        $cacheKey = "plan_visibility:{$restaurantId}:{$planId}";

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(self::CACHE_TTL_MINUTES),
            fn () => $this->buildVisibility($restaurantId, $plan),
        );
    }

    /** @return array<string, mixed> */
    public function compareToPlan(int $restaurantId, int $targetPlanId): array
    {
        $targetPlan = $this->subscriptions->getPlanById($targetPlanId);
        if (! $targetPlan) {
            return ['error' => 'Plan not found'];
        }

        $result = $this->resolve($restaurantId, $targetPlan);

        return [
            'plan_id' => $targetPlanId,
            'plan_name' => $targetPlan['name'] ?? '',
            'plan_slug' => $targetPlan['slug'] ?? '',
            'summary' => $result->summary,
            'has_over_limit' => $result->hasHiddenContent(),
        ];
    }

    public function forgetCache(int $restaurantId): void
    {
        $subscription = $this->subscriptions->getRestaurantSubscription($restaurantId);
        $planId = (int) ($subscription['plan_id'] ?? 0);

        Cache::forget("plan_visibility:{$restaurantId}:{$planId}");
        Cache::forget("plan_visibility:{$restaurantId}:0");

        $planIds = DB::table('subscription_plans')->pluck('id');
        foreach ($planIds as $id) {
            Cache::forget("plan_visibility:{$restaurantId}:target:".(int) $id);
            Cache::forget("plan_visibility:{$restaurantId}:".(int) $id);
        }
    }

    public function forgetCacheForPlan(int $planId): void
    {
        if ($planId <= 0) {
            return;
        }

        $restaurantIds = DB::table('subscriptions')
            ->where('plan_id', $planId)
            ->distinct()
            ->pluck('restaurant_id');

        foreach ($restaurantIds as $restaurantId) {
            $this->forgetCache((int) $restaurantId);
        }
    }

    /** @param  array<string, mixed>  $plan */
    private function buildVisibility(int $restaurantId, array $plan): PlanVisibilityResult
    {
        $planId = (int) ($plan['id'] ?? 0);
        $maxCategories = (int) ($plan['max_categories'] ?? -1);
        $maxMenuItems = (int) ($plan['max_menu_items'] ?? -1);

        $categories = DB::table('categories')
            ->where('restaurant_id', $restaurantId)
            ->orderByRaw('COALESCE(display_order, 999999) ASC')
            ->orderBy('id')
            ->get(['id', 'is_active']);

        $items = DB::table('menu_items')
            ->where('restaurant_id', $restaurantId)
            ->orderByRaw('COALESCE(display_order, 999999) ASC')
            ->orderBy('id')
            ->get(['id', 'category_id', 'is_available']);

        if ($maxCategories === -1 && $maxMenuItems === -1) {
            return PlanVisibilityResult::allVisible(
                $restaurantId,
                $planId,
                $categories->all(),
                $items->all(),
                $maxCategories,
                $maxMenuItems,
            );
        }

        $categoryMeta = [];
        $visibleCategoryIds = [];
        $categoryIndex = 0;
        $hiddenCategoryCount = 0;

        foreach ($categories as $cat) {
            $id = (int) $cat->id;
            $active = (bool) ($cat->is_active ?? 1);
            $overCategoryCap = $maxCategories !== -1 && $categoryIndex >= $maxCategories;

            if ($overCategoryCap) {
                $categoryMeta[$id] = [
                    'is_plan_hidden' => true,
                    'hidden_reason' => 'category_limit',
                    'is_visible_on_public_menu' => false,
                ];
                $hiddenCategoryCount++;
            } else {
                $categoryMeta[$id] = [
                    'is_plan_hidden' => false,
                    'hidden_reason' => null,
                    'is_visible_on_public_menu' => $active,
                ];
                if ($active) {
                    $visibleCategoryIds[$id] = true;
                }
                $categoryIndex++;
            }
        }

        $itemMeta = [];
        $itemSlotIndex = 0;

        foreach ($items as $item) {
            $id = (int) $item->id;
            $catId = (int) $item->category_id;
            $available = (bool) ($item->is_available ?? 1);
            $catMeta = $categoryMeta[$catId] ?? null;

            if ($catMeta === null || ($catMeta['hidden_reason'] ?? null) === 'category_limit') {
                $itemMeta[$id] = [
                    'is_plan_hidden' => true,
                    'hidden_reason' => 'category_limit',
                    'is_visible_on_public_menu' => false,
                ];

                continue;
            }

            $overItemCap = $maxMenuItems !== -1 && $itemSlotIndex >= $maxMenuItems;

            if ($overItemCap) {
                $itemMeta[$id] = [
                    'is_plan_hidden' => true,
                    'hidden_reason' => 'menu_item_limit',
                    'is_visible_on_public_menu' => false,
                ];
            } else {
                $itemMeta[$id] = [
                    'is_plan_hidden' => false,
                    'hidden_reason' => null,
                    'is_visible_on_public_menu' => $available && ($categoryMeta[$catId]['is_visible_on_public_menu'] ?? false),
                ];
                $itemSlotIndex++;
            }
        }

        $hiddenItemCount = 0;
        $visibleItemCount = 0;
        foreach ($itemMeta as $meta) {
            if ($meta['is_plan_hidden']) {
                $hiddenItemCount++;
            }
            if ($meta['is_visible_on_public_menu']) {
                $visibleItemCount++;
            }
        }

        $catUsed = $categories->count();
        $itemUsed = $items->count();

        return new PlanVisibilityResult(
            categories: $categoryMeta,
            menuItems: $itemMeta,
            summary: [
                'plan_id' => $planId,
                'restaurant_id' => $restaurantId,
                'categories' => [
                    'used' => $catUsed,
                    'limit' => $maxCategories === -1 ? 'unlimited' : $maxCategories,
                    'visible_count' => $catUsed - $hiddenCategoryCount,
                    'hidden_count' => $hiddenCategoryCount,
                ],
                'menu_items' => [
                    'used' => $itemUsed,
                    'limit' => $maxMenuItems === -1 ? 'unlimited' : $maxMenuItems,
                    'visible_count' => $visibleItemCount,
                    'hidden_count' => $hiddenItemCount,
                ],
            ],
            planId: $planId,
        );
    }

    /** @param  array<string, mixed>  $subscription */
    private function planLimitsFromSubscription(array $subscription): array
    {
        return [
            'id' => (int) ($subscription['plan_id'] ?? 0),
            'max_categories' => (int) ($subscription['max_categories'] ?? -1),
            'max_menu_items' => (int) ($subscription['max_menu_items'] ?? -1),
        ];
    }
}
