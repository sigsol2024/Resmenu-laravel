<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Section;
use App\Services\DisplayOrderService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\Unit\MenuImport\MenuImportDatabase;

class DisplayOrderServiceTest extends TestCase
{
    use MenuImportDatabase;

    private DisplayOrderService $orders;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpMenuDatabase();
        $this->orders = new DisplayOrderService;
    }

    public function test_moving_up_shifts_the_others_down(): void
    {
        $sectionId = $this->makeSection('Food');
        $ids = $this->categories($sectionId, ['A', 'B', 'C', 'D']);

        $d = Category::findOrFail($ids['D']);
        $d->update(['display_order' => 2]);
        $this->orders->placeCategory($d, 2);

        $this->assertSame(['A' => 1, 'D' => 2, 'B' => 3, 'C' => 4], $this->categoryOrder($sectionId));
        $this->assertSame(2, $d->display_order);
    }

    public function test_moving_down_closes_the_gap_it_leaves(): void
    {
        $sectionId = $this->makeSection('Food');
        $ids = $this->categories($sectionId, ['A', 'B', 'C', 'D']);

        $this->orders->placeCategory(Category::findOrFail($ids['A']), 3);

        $this->assertSame(['B' => 1, 'C' => 2, 'A' => 3, 'D' => 4], $this->categoryOrder($sectionId));
    }

    public function test_out_of_range_positions_are_clamped(): void
    {
        $sectionId = $this->makeSection('Food');
        $ids = $this->categories($sectionId, ['A', 'B', 'C']);

        $this->orders->placeCategory(Category::findOrFail($ids['A']), 99);
        $this->assertSame(['B' => 1, 'C' => 2, 'A' => 3], $this->categoryOrder($sectionId));

        $this->orders->placeCategory(Category::findOrFail($ids['A']), 0);
        $this->assertSame(['A' => 1, 'B' => 2, 'C' => 3], $this->categoryOrder($sectionId));
    }

    public function test_blank_position_keeps_the_slot_and_repairs_duplicates(): void
    {
        $sectionId = $this->makeSection('Food');
        $a = $this->makeCategory($sectionId, 'A', 1, ['display_order' => 16]);
        $this->makeCategory($sectionId, 'B', 1, ['display_order' => 16]);
        $this->makeCategory($sectionId, 'C', 1, ['display_order' => 14]);

        $this->orders->placeCategory(Category::findOrFail($a), null);

        $this->assertSame(['C' => 1, 'A' => 2, 'B' => 3], $this->categoryOrder($sectionId));
    }

    public function test_new_record_without_position_goes_last(): void
    {
        $sectionId = $this->makeSection('Food');
        $this->categories($sectionId, ['A', 'B']);
        $new = Category::findOrFail($this->makeCategory($sectionId, 'New', 1, ['display_order' => 1]));

        $this->orders->placeCategory($new, null, true);

        $this->assertSame(['A' => 1, 'B' => 2, 'New' => 3], $this->categoryOrder($sectionId));
    }

    public function test_groups_are_independent(): void
    {
        $food = $this->makeSection('Food');
        $drinks = $this->makeSection('Drinks');
        $foodIds = $this->categories($food, ['A', 'B']);
        $this->categories($drinks, ['X', 'Y']);
        $this->makeCategory($this->makeSection('Elsewhere', 2), 'Other', 2, ['display_order' => 1]);

        $this->orders->placeCategory(Category::findOrFail($foodIds['B']), 1);

        $this->assertSame(['B' => 1, 'A' => 2], $this->categoryOrder($food));
        $this->assertSame(['X' => 1, 'Y' => 2], $this->categoryOrder($drinks));
        $this->assertSame(1, (int) DB::table('categories')->where('restaurant_id', 2)->value('display_order'));
    }

    public function test_sections_and_menu_items_follow_the_same_rule(): void
    {
        $s1 = $this->makeSection('One', 1, ['display_order' => 1]);
        $s2 = $this->makeSection('Two', 1, ['display_order' => 2]);
        $s3 = $this->makeSection('Three', 1, ['display_order' => 3]);
        $this->orders->placeSection(Section::findOrFail($s3), 1);
        $this->assertSame([$s3 => 1, $s1 => 2, $s2 => 3], DB::table('sections')->orderBy('display_order')->pluck('display_order', 'id')->map(fn ($v) => (int) $v)->all());

        $categoryId = $this->makeCategory($s1, 'Soups');
        $i1 = $this->makeItem($categoryId, 'Egusi', '2500', null, 1, ['display_order' => 1]);
        $i2 = $this->makeItem($categoryId, 'Ogbono', '2500', null, 1, ['display_order' => 2]);
        $this->orders->placeMenuItem(MenuItem::findOrFail($i2), 1);
        $this->assertSame([$i2 => 1, $i1 => 2], DB::table('menu_items')->orderBy('display_order')->pluck('display_order', 'id')->map(fn ($v) => (int) $v)->all());
    }

    public function test_resequence_restaurant_keeps_visible_order_and_reports_changes(): void
    {
        $food = $this->makeSection('Food', 1, ['display_order' => 5]);
        $drinks = $this->makeSection('Drinks', 1, ['display_order' => 5]);
        $this->makeCategory($food, 'Soft drinks', 1, ['display_order' => 16]);
        $this->makeCategory($food, 'Appetizers', 1, ['display_order' => 16]);
        $this->makeCategory($food, 'Pasta', 1, ['display_order' => 15]);
        $this->makeCategory($drinks, 'Whiskey', 1, ['display_order' => 19]);
        $cat = $this->makeCategory($drinks, 'Champagne', 1, ['display_order' => 18]);
        $this->makeItem($cat, 'Moet', '1', null, 1, ['display_order' => 4]);
        $this->makeItem($cat, 'Dom', '1', null, 1, ['display_order' => 4]);

        $changed = $this->orders->resequenceRestaurant(1);

        $this->assertSame(['Food' => 1, 'Drinks' => 2], DB::table('sections')->orderBy('display_order')->pluck('display_order', 'name')->map(fn ($v) => (int) $v)->all());
        $this->assertSame(['Pasta' => 1, 'Soft drinks' => 2, 'Appetizers' => 3], $this->categoryOrder($food));
        $this->assertSame(['Champagne' => 1, 'Whiskey' => 2], $this->categoryOrder($drinks));
        $this->assertSame(['Moet' => 1, 'Dom' => 2], DB::table('menu_items')->orderBy('display_order')->pluck('display_order', 'name')->map(fn ($v) => (int) $v)->all());
        $this->assertSame(9, $changed);
        $this->assertSame(0, $this->orders->resequenceRestaurant(1));
    }

    /** @return array<string, int> */
    private function categories(int $sectionId, array $names): array
    {
        $ids = [];
        foreach ($names as $i => $name) {
            $ids[$name] = $this->makeCategory($sectionId, $name, 1, ['display_order' => $i + 1]);
        }

        return $ids;
    }

    /** @return array<string, int> */
    private function categoryOrder(int $sectionId): array
    {
        return DB::table('categories')
            ->where('section_id', $sectionId)
            ->orderBy('display_order')
            ->pluck('display_order', 'name')
            ->map(fn ($v) => (int) $v)
            ->all();
    }
}
