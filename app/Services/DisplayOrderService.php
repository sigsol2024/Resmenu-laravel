<?php

namespace App\Services;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Section;
use Illuminate\Database\Eloquent\Builder;

class DisplayOrderService
{
    public function nextSectionOrder(int $restaurantId): int
    {
        return $this->next(Section::query()->where('restaurant_id', $restaurantId));
    }

    public function nextCategoryOrder(int $restaurantId, ?int $sectionId = null): int
    {
        $query = Category::query()->where('restaurant_id', $restaurantId);

        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        return $this->next($query);
    }

    public function nextMenuItemOrder(int $restaurantId, ?int $categoryId = null): int
    {
        $query = MenuItem::query()->where('restaurant_id', $restaurantId);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $this->next($query);
    }

    /** @return array<int, int> */
    public function nextCategoryOrderPerSection(int $restaurantId): array
    {
        return $this->nextPerGroup(
            Category::query()->where('restaurant_id', $restaurantId),
            'section_id',
        );
    }

    /** @return array<int, int> */
    public function nextMenuItemOrderPerCategory(int $restaurantId): array
    {
        return $this->nextPerGroup(
            MenuItem::query()->where('restaurant_id', $restaurantId),
            'category_id',
        );
    }

    private function next(Builder $query): int
    {
        return max(0, (int) $query->max('display_order')) + 1;
    }

    /** @return array<int, int> */
    private function nextPerGroup(Builder $query, string $column): array
    {
        $rows = $query->toBase()
            ->selectRaw($column.' as group_id, MAX(display_order) as max_order')
            ->groupBy($column)
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row->group_id] = max(0, (int) $row->max_order) + 1;
        }

        return $result;
    }
}
