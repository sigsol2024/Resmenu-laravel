<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureActiveSubscription;
use App\Models\Manager;
use App\Services\ManagerFeatureAccess;
use App\Services\PlanVisibilityService;
use App\Services\SubscriptionService;
use App\Support\PlanVisibilityResult;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;
use Tests\Unit\MenuImport\MenuImportDatabase;

/** Positions entered in the manager forms keep each group numbered 1..n. */
class ManagerDisplayOrderTest extends TestCase
{
    use MenuImportDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        $this->setUpMenuDatabase();

        Schema::table('restaurants', function (Blueprint $t) {
            $t->timestamp('suspended_at')->nullable();
            $t->boolean('is_active')->default(true);
        });
        Schema::create('managers', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('restaurant_id');
            $t->string('username');
            $t->string('email');
            $t->string('password_hash')->nullable();
            $t->string('remember_token')->nullable();
            $t->timestamp('email_verified_at')->nullable();
            $t->timestamps();
        });
        DB::table('managers')->insert([
            ['id' => 1, 'restaurant_id' => 1, 'username' => 'owner', 'email' => 'owner@example.com', 'email_verified_at' => now()],
        ]);

        $subscriptions = Mockery::mock(SubscriptionService::class)->makePartial();
        $subscriptions->shouldReceive('canAddCategory', 'canAddMenuItem')->andReturn(true);
        $subscriptions->shouldReceive('getRestaurantSubscription')->andReturn(null);
        $this->app->instance(SubscriptionService::class, $subscriptions);
        $features = Mockery::mock(ManagerFeatureAccess::class);
        $features->shouldReceive('foodOrderingUsable', 'tableReservationsUsable', 'planHasFoodOrdering', 'planHasTableReservations')->andReturn(false);
        $this->app->instance(ManagerFeatureAccess::class, $features);
        $visibility = Mockery::mock(PlanVisibilityService::class);
        $visibility->shouldReceive('forgetCache');
        $visibility->shouldReceive('resolve')->andReturn(new PlanVisibilityResult([], [], []));
        $this->app->instance(PlanVisibilityService::class, $visibility);

        $this->withoutMiddleware(EnsureActiveSubscription::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_editing_a_category_position_shifts_the_rest_of_its_section(): void
    {
        $food = $this->makeSection('Food');
        $ids = $this->categories($food, ['A', 'B', 'C', 'D']);

        $this->asManager()->put(route('manager.categories.update', $ids['D']), $this->categoryForm('D', $food, 1))
            ->assertRedirect(route('manager.categories.index'));

        $this->assertSame(['D' => 1, 'A' => 2, 'B' => 3, 'C' => 4], $this->order('categories', ['section_id' => $food]));
    }

    public function test_new_category_at_a_taken_position_pushes_the_current_one_down(): void
    {
        $food = $this->makeSection('Food');
        $this->categories($food, ['A', 'B', 'C']);

        $this->asManager()->post(route('manager.categories.store'), $this->categoryForm('New', $food, 2))
            ->assertRedirect(route('manager.categories.index'));
        $this->asManager()->post(route('manager.categories.store'), $this->categoryForm('Last', $food, ''))
            ->assertRedirect(route('manager.categories.index'));

        $this->assertSame(['A' => 1, 'New' => 2, 'B' => 3, 'C' => 4, 'Last' => 5], $this->order('categories', ['section_id' => $food]));
    }

    public function test_moving_a_category_to_another_section_renumbers_both(): void
    {
        $food = $this->makeSection('Food');
        $drinks = $this->makeSection('Drinks');
        $foodIds = $this->categories($food, ['A', 'B', 'C']);
        $this->categories($drinks, ['X', 'Y']);

        $this->asManager()->put(route('manager.categories.update', $foodIds['A']), $this->categoryForm('A', $drinks, ''));

        $this->assertSame(['B' => 1, 'C' => 2], $this->order('categories', ['section_id' => $food]));
        $this->assertSame(['X' => 1, 'Y' => 2, 'A' => 3], $this->order('categories', ['section_id' => $drinks]));
    }

    public function test_deleting_closes_the_gap(): void
    {
        $food = $this->makeSection('Food');
        $ids = $this->categories($food, ['A', 'B', 'C']);
        $this->makeItem($ids['A'], 'Rice', '100', null, 1, ['display_order' => 1]);
        $soupId = $this->makeItem($ids['A'], 'Soup', '100', null, 1, ['display_order' => 2]);
        $this->makeItem($ids['A'], 'Stew', '100', null, 1, ['display_order' => 3]);

        $this->asManager()->delete(route('manager.categories.destroy', $ids['B']));
        $this->asManager()->delete(route('manager.menu-items.destroy', $soupId));

        $this->assertSame(['A' => 1, 'C' => 2], $this->order('categories', ['section_id' => $food]));
        $this->assertSame(['Rice' => 1, 'Stew' => 2], $this->order('menu_items', ['category_id' => $ids['A']]));
    }

    public function test_menu_item_positions_and_category_moves(): void
    {
        $food = $this->makeSection('Food');
        $cats = $this->categories($food, ['Soups', 'Grills']);
        $egusi = $this->makeItem($cats['Soups'], 'Egusi', '100', null, 1, ['display_order' => 1]);
        $this->makeItem($cats['Soups'], 'Ogbono', '100', null, 1, ['display_order' => 2]);
        $okro = $this->makeItem($cats['Soups'], 'Okro', '100', null, 1, ['display_order' => 3]);
        $this->makeItem($cats['Grills'], 'Suya', '100', null, 1, ['display_order' => 1]);

        $this->asManager()->put(route('manager.menu-items.update', $okro), $this->itemForm('Okro', $cats['Soups'], 1));
        $this->assertSame(['Okro' => 1, 'Egusi' => 2, 'Ogbono' => 3], $this->order('menu_items', ['category_id' => $cats['Soups']]));

        $this->asManager()->put(route('manager.menu-items.update', $egusi), $this->itemForm('Egusi', $cats['Grills'], 1));
        $this->assertSame(['Okro' => 1, 'Ogbono' => 2], $this->order('menu_items', ['category_id' => $cats['Soups']]));
        $this->assertSame(['Egusi' => 1, 'Suya' => 2], $this->order('menu_items', ['category_id' => $cats['Grills']]));

        $this->asManager()->post(route('manager.menu-items.store'), $this->itemForm('Pepper Soup', $cats['Soups'], 2));
        $this->assertSame(['Okro' => 1, 'Pepper Soup' => 2, 'Ogbono' => 3], $this->order('menu_items', ['category_id' => $cats['Soups']]));
    }

    public function test_section_positions(): void
    {
        $one = $this->makeSection('One', 1, ['display_order' => 1]);
        $this->makeSection('Two', 1, ['display_order' => 2]);
        $this->makeSection('Three', 1, ['display_order' => 3]);

        $this->asManager()->post(route('manager.sections.store'), ['name' => 'New', 'display_order' => 1, 'is_active' => 1]);
        $this->assertSame(['New' => 1, 'One' => 2, 'Two' => 3, 'Three' => 4], $this->order('sections', ['restaurant_id' => 1]));

        $this->asManager()->put(route('manager.sections.update', $one), ['name' => 'One', 'display_order' => 4, 'is_active' => 1]);
        $this->assertSame(['New' => 1, 'Two' => 2, 'Three' => 3, 'One' => 4], $this->order('sections', ['restaurant_id' => 1]));
    }

    public function test_list_pages_group_by_section_then_position(): void
    {
        $drinks = $this->makeSection('Drinks', 1, ['display_order' => 2]);
        $food = $this->makeSection('Food', 1, ['display_order' => 1]);
        $drinkCats = $this->categories($drinks, ['Wine', 'Beer']);
        $foodCats = $this->categories($food, ['Soups', 'Grills']);
        $this->makeItem($drinkCats['Wine'], 'Merlot', '100', null, 1, ['display_order' => 1]);
        $this->makeItem($foodCats['Soups'], 'Egusi', '100', null, 1, ['display_order' => 2]);
        $this->makeItem($foodCats['Soups'], 'Okro', '100', null, 1, ['display_order' => 1]);

        $this->asManager()->get(route('manager.categories.index'))
            ->assertOk()
            ->assertSeeInOrder(['Soups', 'Grills', 'Wine', 'Beer']);
        $this->asManager()->get(route('manager.menu-items.index'))
            ->assertOk()
            ->assertSeeInOrder(['Okro', 'Egusi', 'Merlot']);
    }

    public function test_resequence_command_dry_run_then_apply(): void
    {
        $food = $this->makeSection('Food');
        $this->makeCategory($food, 'Soft drinks', 1, ['display_order' => 16]);
        $this->makeCategory($food, 'Appetizers', 1, ['display_order' => 16]);
        $other = $this->makeSection('Other', 2, ['display_order' => 7]);

        $this->artisan('menu:resequence-display-orders', ['restaurant' => 1, '--dry-run' => true])
            ->expectsOutputToContain('Dry run: 2 row(s)')
            ->assertSuccessful();
        $this->assertSame(['Soft drinks' => 16, 'Appetizers' => 16], $this->order('categories', ['section_id' => $food]));

        $this->artisan('menu:resequence-display-orders', ['restaurant' => 1])->assertSuccessful();
        $this->assertSame(['Soft drinks' => 1, 'Appetizers' => 2], $this->order('categories', ['section_id' => $food]));
        $this->assertSame(7, (int) DB::table('sections')->where('id', $other)->value('display_order'));

        $this->artisan('menu:resequence-display-orders')->assertFailed();
    }

    private function asManager(): static
    {
        return $this->actingAs(Manager::findOrFail(1), 'manager');
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

    private function categoryForm(string $name, int $sectionId, int|string $position): array
    {
        return ['name' => $name, 'section_id' => $sectionId, 'display_order' => $position, 'is_active' => 1];
    }

    private function itemForm(string $name, int $categoryId, int|string $position): array
    {
        return ['name' => $name, 'category_id' => $categoryId, 'price' => '100', 'display_order' => $position, 'is_available' => 1];
    }

    /** @return array<string, int> */
    private function order(string $table, array $where): array
    {
        return DB::table($table)->where($where)->orderBy('display_order')->pluck('display_order', 'name')->map(fn ($v) => (int) $v)->all();
    }
}
