<?php

namespace Tests\Unit;

use App\Support\PlanVisibilityResult;
use Tests\TestCase;

class PlanVisibilityResultTest extends TestCase
{
    public function test_all_visible_marks_entities_and_summary(): void
    {
        $categories = [
            (object) ['id' => 1, 'is_active' => 1],
            (object) ['id' => 2, 'is_active' => 0],
        ];
        $items = [
            (object) ['id' => 10, 'category_id' => 1, 'is_available' => 1],
            (object) ['id' => 11, 'category_id' => 2, 'is_available' => 1],
        ];

        $result = PlanVisibilityResult::allVisible(5, 3, $categories, $items, -1, -1);

        $this->assertFalse($result->hasHiddenContent());
        $this->assertFalse($result->getCategoryMeta(1)['is_plan_hidden']);
        $this->assertTrue($result->isCategoryVisibleOnPublicMenu(1));
        $this->assertFalse($result->isCategoryVisibleOnPublicMenu(2));
        $this->assertTrue($result->isMenuItemVisibleOnPublicMenu(10));
        $this->assertFalse($result->isMenuItemVisibleOnPublicMenu(11));
        $this->assertSame(2, $result->summary['categories']['used']);
        $this->assertSame('unlimited', $result->summary['categories']['limit']);
        $this->assertSame(0, $result->summary['menu_items']['hidden_count']);
    }

    public function test_has_hidden_content_when_summary_counts_positive(): void
    {
        $result = new PlanVisibilityResult(
            categories: [],
            menuItems: [],
            summary: [
                'categories' => ['hidden_count' => 1, 'visible_count' => 0, 'used' => 1, 'limit' => 1],
                'menu_items' => ['hidden_count' => 0, 'visible_count' => 0, 'used' => 0, 'limit' => 1],
            ],
        );

        $this->assertTrue($result->hasHiddenContent());
    }

    public function test_unknown_entity_ids_default_to_not_visible(): void
    {
        $result = new PlanVisibilityResult(
            categories: [],
            menuItems: [],
            summary: [
                'categories' => ['hidden_count' => 0, 'visible_count' => 0, 'used' => 0, 'limit' => 1],
                'menu_items' => ['hidden_count' => 0, 'visible_count' => 0, 'used' => 0, 'limit' => 1],
            ],
        );

        $this->assertFalse($result->isMenuItemVisibleOnPublicMenu(99999));
        $this->assertFalse($result->isCategoryVisibleOnPublicMenu(99999));
        $this->assertTrue($result->getMenuItemMeta(99999)['is_plan_hidden']);
        $this->assertFalse($result->getMenuItemMeta(99999)['is_visible_on_public_menu']);
    }
}
