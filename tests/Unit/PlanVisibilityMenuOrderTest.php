<?php

namespace Tests\Unit;

use App\Services\PlanVisibilityService;
use App\Services\SubscriptionService;
use Mockery;
use Tests\TestCase;
use Tests\Unit\MenuImport\MenuImportDatabase;

/** Over-limit content is the tail of the menu in section → category → item order. */
class PlanVisibilityMenuOrderTest extends TestCase
{
    use MenuImportDatabase;

    private PlanVisibilityService $visibility;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpMenuDatabase();
        $this->visibility = new PlanVisibilityService(Mockery::mock(SubscriptionService::class));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_category_limit_hides_the_last_categories_on_the_menu(): void
    {
        $drinks = $this->makeSection('Drinks', 1, ['display_order' => 2]);
        $food = $this->makeSection('Food', 1, ['display_order' => 1]);
        $wine = $this->makeCategory($drinks, 'Wine', 1, ['display_order' => 1]);
        $beer = $this->makeCategory($drinks, 'Beer', 1, ['display_order' => 2]);
        $soups = $this->makeCategory($food, 'Soups', 1, ['display_order' => 1]);
        $grills = $this->makeCategory($food, 'Grills', 1, ['display_order' => 2]);

        $result = $this->visibility->resolve(1, ['id' => 9, 'max_categories' => 3, 'max_menu_items' => -1]);

        foreach ([$soups, $grills, $wine] as $id) {
            $this->assertFalse($result->getCategoryMeta($id)['is_plan_hidden']);
        }
        $this->assertTrue($result->getCategoryMeta($beer)['is_plan_hidden']);
    }

    public function test_item_limit_hides_the_last_items_not_every_category_tail(): void
    {
        $food = $this->makeSection('Food', 1, ['display_order' => 1]);
        $soups = $this->makeCategory($food, 'Soups', 1, ['display_order' => 1]);
        $grills = $this->makeCategory($food, 'Grills', 1, ['display_order' => 2]);
        $egusi = $this->makeItem($soups, 'Egusi', '100', null, 1, ['display_order' => 1]);
        $okro = $this->makeItem($soups, 'Okro', '100', null, 1, ['display_order' => 2]);
        $ogbono = $this->makeItem($soups, 'Ogbono', '100', null, 1, ['display_order' => 3]);
        $suya = $this->makeItem($grills, 'Suya', '100', null, 1, ['display_order' => 1]);
        $asun = $this->makeItem($grills, 'Asun', '100', null, 1, ['display_order' => 2]);

        $result = $this->visibility->resolve(1, ['id' => 9, 'max_categories' => -1, 'max_menu_items' => 4]);

        foreach ([$egusi, $okro, $ogbono, $suya] as $id) {
            $this->assertFalse($result->getMenuItemMeta($id)['is_plan_hidden']);
        }
        $this->assertTrue($result->getMenuItemMeta($asun)['is_plan_hidden']);
    }
}
