<?php

namespace App\Services;

use App\Models\Restaurant;

class ManagerFeatureAccess
{
    public function __construct(private SubscriptionService $subscriptions) {}

    public function planHasFoodOrdering(int $restaurantId): bool
    {
        return $this->subscriptions->hasFeatureAccess($restaurantId, 'food_ordering');
    }

    public function planHasTableReservations(int $restaurantId): bool
    {
        return $this->subscriptions->hasFeatureAccess($restaurantId, 'table_reservations');
    }

    public function foodOrderingUsable(int $restaurantId): bool
    {
        if (! $this->planHasFoodOrdering($restaurantId)) {
            return false;
        }

        $restaurant = Restaurant::find($restaurantId);
        if (! $restaurant) {
            return false;
        }

        return (int) ($restaurant->enable_food_ordering ?? 1) === 1;
    }

    public function tableReservationsUsable(int $restaurantId): bool
    {
        if (! $this->planHasTableReservations($restaurantId)) {
            return false;
        }

        $restaurant = Restaurant::find($restaurantId);
        if (! $restaurant) {
            return false;
        }

        return (int) ($restaurant->enable_table_reservations ?? 1) === 1;
    }

    /** @return array{show_overlay:bool, feature:string, message:string} */
    public function ordersPageContext(int $restaurantId): array
    {
        if ($this->planHasFoodOrdering($restaurantId)) {
            return ['show_overlay' => false, 'feature' => 'food_ordering', 'message' => ''];
        }

        return [
            'show_overlay' => true,
            'feature' => 'food_ordering',
            'message' => 'Food ordering is not included in your current plan. Upgrade to Professional or Enterprise to accept orders.',
        ];
    }

    /** @return array{show_overlay:bool, feature:string, message:string} */
    public function reservationsPageContext(int $restaurantId): array
    {
        if ($this->planHasTableReservations($restaurantId)) {
            return ['show_overlay' => false, 'feature' => 'table_reservations', 'message' => ''];
        }

        return [
            'show_overlay' => true,
            'feature' => 'table_reservations',
            'message' => 'Table reservations are available on the Enterprise plan. Upgrade to enable reservations.',
        ];
    }
}
