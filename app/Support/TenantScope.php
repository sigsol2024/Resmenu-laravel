<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TenantScope
{
    public static function assertSectionBelongsToRestaurant(int $sectionId, int $restaurantId): void
    {
        if ($sectionId <= 0) {
            return;
        }

        $exists = DB::table('sections')
            ->where('id', $sectionId)
            ->where('restaurant_id', $restaurantId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages(['section_id' => 'Invalid section for this restaurant.']);
        }
    }

    public static function assertCategoryBelongsToRestaurant(int $categoryId, int $restaurantId): void
    {
        if ($categoryId <= 0) {
            return;
        }

        $exists = DB::table('categories')
            ->where('id', $categoryId)
            ->where('restaurant_id', $restaurantId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages(['category_id' => 'Invalid category for this restaurant.']);
        }
    }

    /** @param  list<int|string>  $sectionIds */
    public static function assertSectionsBelongToRestaurant(array $sectionIds, int $restaurantId, string $field = 'secondary_section_ids'): void
    {
        foreach ($sectionIds as $sectionId) {
            $id = (int) $sectionId;
            if ($id <= 0) {
                continue;
            }

            $exists = DB::table('sections')
                ->where('id', $id)
                ->where('restaurant_id', $restaurantId)
                ->exists();

            if (! $exists) {
                throw ValidationException::withMessages([$field => 'One or more sections are invalid for this restaurant.']);
            }
        }
    }
}
