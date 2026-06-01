<?php

namespace Tests\Unit;

use App\Services\PlanVisibilityService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PlanVisibilityServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    private function service(): PlanVisibilityService
    {
        return app(PlanVisibilityService::class);
    }

    private function dbAvailable(): bool
    {
        try {
            return Schema::hasTable('categories') && Schema::hasTable('menu_items') && Schema::hasTable('subscription_plans');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @return array{restaurant_id:int, plan_id:int} */
    private function seedRestaurantWithLimits(int $maxCategories, int $maxMenuItems): array
    {
        $slug = 'vis-test-'.uniqid();
        $restaurantId = DB::table('restaurants')->insertGetId([
            'name' => 'Visibility Test',
            'slug' => $slug,
            'email' => $slug.'@test.com',
            'is_active' => 1,
            'template_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $planId = DB::table('subscription_plans')->insertGetId([
            'name' => 'Test Plan',
            'slug' => 'test-plan-'.uniqid(),
            'monthly_price' => 100,
            'annual_price' => 1000,
            'max_categories' => $maxCategories,
            'max_menu_items' => $maxMenuItems,
            'max_qr_styles' => 5,
            'max_templates' => 5,
            'is_active' => 1,
            'display_order' => 99,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subscriptions')->insert([
            'restaurant_id' => $restaurantId,
            'plan_id' => $planId,
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['restaurant_id' => $restaurantId, 'plan_id' => $planId];
    }

    public function test_unlimited_plan_marks_nothing_hidden(): void
    {
        if (! $this->dbAvailable()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedRestaurantWithLimits(-1, -1);
        $restaurantId = $ctx['restaurant_id'];

        for ($i = 0; $i < 3; $i++) {
            $catId = DB::table('categories')->insertGetId([
                'restaurant_id' => $restaurantId,
                'section_id' => null,
                'name' => 'Cat '.$i,
                'slug' => 'cat-'.$i.'-'.uniqid(),
                'display_order' => $i,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('menu_items')->insert([
                'restaurant_id' => $restaurantId,
                'category_id' => $catId,
                'name' => 'Item '.$i,
                'slug' => 'item-'.$i.'-'.uniqid(),
                'price' => 10,
                'display_order' => $i,
                'is_available' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $result = $this->service()->resolve($restaurantId);

        $this->assertFalse($result->hasHiddenContent());
        $this->assertSame(0, $result->summary['categories']['hidden_count']);
        $this->assertSame(0, $result->summary['menu_items']['hidden_count']);
    }

    public function test_category_limit_hides_excess_categories_first(): void
    {
        if (! $this->dbAvailable()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedRestaurantWithLimits(2, 100);
        $restaurantId = $ctx['restaurant_id'];

        $catIds = [];
        for ($i = 0; $i < 4; $i++) {
            $catIds[] = DB::table('categories')->insertGetId([
                'restaurant_id' => $restaurantId,
                'section_id' => null,
                'name' => 'Cat '.$i,
                'slug' => 'cat-'.$i.'-'.uniqid(),
                'display_order' => $i,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $result = $this->service()->resolve($restaurantId);

        $this->assertSame(2, $result->summary['categories']['hidden_count']);
        $this->assertFalse($result->getCategoryMeta($catIds[0])['is_plan_hidden']);
        $this->assertFalse($result->getCategoryMeta($catIds[1])['is_plan_hidden']);
        $this->assertTrue($result->getCategoryMeta($catIds[2])['is_plan_hidden']);
        $this->assertSame('category_limit', $result->getCategoryMeta($catIds[2])['hidden_reason']);
    }

    public function test_menu_item_limit_applies_after_category_filter(): void
    {
        if (! $this->dbAvailable()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedRestaurantWithLimits(10, 2);
        $restaurantId = $ctx['restaurant_id'];

        $catId = DB::table('categories')->insertGetId([
            'restaurant_id' => $restaurantId,
            'section_id' => null,
            'name' => 'Main',
            'slug' => 'main-'.uniqid(),
            'display_order' => 0,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $itemIds = [];
        for ($i = 0; $i < 4; $i++) {
            $itemIds[] = DB::table('menu_items')->insertGetId([
                'restaurant_id' => $restaurantId,
                'category_id' => $catId,
                'name' => 'Item '.$i,
                'slug' => 'item-'.$i.'-'.uniqid(),
                'price' => 10,
                'display_order' => $i,
                'is_available' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $result = $this->service()->resolve($restaurantId);

        $this->assertSame(2, $result->summary['menu_items']['hidden_count']);
        $this->assertFalse($result->getMenuItemMeta($itemIds[0])['is_plan_hidden']);
        $this->assertFalse($result->getMenuItemMeta($itemIds[1])['is_plan_hidden']);
        $this->assertTrue($result->getMenuItemMeta($itemIds[2])['is_plan_hidden']);
        $this->assertSame('menu_item_limit', $result->getMenuItemMeta($itemIds[2])['hidden_reason']);
    }

    public function test_null_display_order_sorts_after_explicit_low_order(): void
    {
        if (! $this->dbAvailable()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedRestaurantWithLimits(1, 10);
        $restaurantId = $ctx['restaurant_id'];

        $low = DB::table('categories')->insertGetId([
            'restaurant_id' => $restaurantId,
            'section_id' => null,
            'name' => 'Low Order',
            'slug' => 'low-'.uniqid(),
            'display_order' => 1,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $nullOrder = DB::table('categories')->insertGetId([
            'restaurant_id' => $restaurantId,
            'section_id' => null,
            'name' => 'Null Order',
            'slug' => 'null-'.uniqid(),
            'display_order' => null,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $result = $this->service()->resolve($restaurantId);

        $this->assertFalse($result->getCategoryMeta($low)['is_plan_hidden']);
        $this->assertTrue($result->getCategoryMeta($nullOrder)['is_plan_hidden']);
    }

    public function test_forget_cache_causes_rebuild(): void
    {
        if (! $this->dbAvailable()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedRestaurantWithLimits(-1, -1);
        $restaurantId = $ctx['restaurant_id'];
        $service = $this->service();

        $first = $service->resolve($restaurantId);
        $service->forgetCache($restaurantId);
        $second = $service->resolve($restaurantId);

        $this->assertEquals($first->summary, $second->summary);
    }

    public function test_compare_to_plan_returns_projected_summary(): void
    {
        if (! $this->dbAvailable()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $basicPlanId = DB::table('subscription_plans')->where('slug', 'basic')->value('id');
        if (! $basicPlanId) {
            $basicPlanId = DB::table('subscription_plans')->insertGetId([
                'name' => 'Basic',
                'slug' => 'basic-'.uniqid(),
                'monthly_price' => 150,
                'annual_price' => 1500,
                'max_categories' => 20,
                'max_menu_items' => 150,
                'max_qr_styles' => 5,
                'max_templates' => 5,
                'is_active' => 1,
                'display_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $ctx = $this->seedRestaurantWithLimits(100, 100);
        $restaurantId = $ctx['restaurant_id'];

        for ($i = 0; $i < 25; $i++) {
            DB::table('categories')->insert([
                'restaurant_id' => $restaurantId,
                'section_id' => null,
                'name' => 'Cat '.$i,
                'slug' => 'cat-'.$i.'-'.uniqid(),
                'display_order' => $i,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $comparison = $this->service()->compareToPlan($restaurantId, (int) $basicPlanId);

        $this->assertTrue($comparison['has_over_limit'] ?? false);
        $this->assertGreaterThan(0, $comparison['summary']['categories']['hidden_count'] ?? 0);
    }
}
