<?php

namespace App\Support;

/**
 * Single source of truth for plan-limit visibility per restaurant.
 *
 * @phpstan-type EntityMeta array{
 *   is_plan_hidden: bool,
 *   hidden_reason: null|string,
 *   is_visible_on_public_menu: bool,
 * }
 */
class PlanVisibilityResult
{
    /** @param  array<int, EntityMeta>  $categories */
    /** @param  array<int, EntityMeta>  $menuItems */
    /** @param  array<string, mixed>  $summary */
    public function __construct(
        public readonly array $categories,
        public readonly array $menuItems,
        public readonly array $summary,
        public readonly int $planId = 0,
    ) {}

    public function isCategoryVisibleOnPublicMenu(int $categoryId): bool
    {
        return (bool) ($this->categories[$categoryId]['is_visible_on_public_menu'] ?? false);
    }

    public function isMenuItemVisibleOnPublicMenu(int $menuItemId): bool
    {
        return (bool) ($this->menuItems[$menuItemId]['is_visible_on_public_menu'] ?? false);
    }

    public function getCategoryMeta(int $categoryId): array
    {
        return $this->categories[$categoryId] ?? self::unknownEntityMeta();
    }

    public function getMenuItemMeta(int $menuItemId): array
    {
        return $this->menuItems[$menuItemId] ?? self::unknownEntityMeta();
    }

    /** @return EntityMeta */
    private static function unknownEntityMeta(): array
    {
        return [
            'is_plan_hidden' => true,
            'hidden_reason' => null,
            'is_visible_on_public_menu' => false,
        ];
    }

    public function hasHiddenContent(): bool
    {
        return ($this->summary['categories']['hidden_count'] ?? 0) > 0
            || ($this->summary['menu_items']['hidden_count'] ?? 0) > 0;
    }

    /** @param  list<object{id:int, category_id:int, is_active?:int|bool}>  $categories */
    /** @param  list<object{id:int, category_id:int, is_available?:int|bool}>  $items */
    public static function allVisible(
        int $restaurantId,
        int $planId,
        array $categories,
        array $items,
        int $maxCategories,
        int $maxMenuItems,
    ): self {
        $categoryMeta = [];
        foreach ($categories as $cat) {
            $id = (int) $cat->id;
            $active = (bool) ($cat->is_active ?? 1);
            $categoryMeta[$id] = [
                'is_plan_hidden' => false,
                'hidden_reason' => null,
                'is_visible_on_public_menu' => $active,
            ];
        }

        $itemMeta = [];
        foreach ($items as $item) {
            $id = (int) $item->id;
            $catId = (int) $item->category_id;
            $catVisible = $categoryMeta[$catId]['is_visible_on_public_menu'] ?? false;
            $available = (bool) ($item->is_available ?? 1);
            $itemMeta[$id] = [
                'is_plan_hidden' => false,
                'hidden_reason' => null,
                'is_visible_on_public_menu' => $catVisible && $available,
            ];
        }

        $catUsed = count($categories);
        $itemUsed = count($items);

        return new self(
            categories: $categoryMeta,
            menuItems: $itemMeta,
            summary: [
                'plan_id' => $planId,
                'restaurant_id' => $restaurantId,
                'categories' => [
                    'used' => $catUsed,
                    'limit' => $maxCategories === -1 ? 'unlimited' : $maxCategories,
                    'visible_count' => $catUsed,
                    'hidden_count' => 0,
                ],
                'menu_items' => [
                    'used' => $itemUsed,
                    'limit' => $maxMenuItems === -1 ? 'unlimited' : $maxMenuItems,
                    'visible_count' => $itemUsed,
                    'hidden_count' => 0,
                ],
            ],
            planId: $planId,
        );
    }
}
