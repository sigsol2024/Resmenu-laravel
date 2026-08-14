<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Manager;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RestaurantTrialAndMenuAvailabilityTest extends TestCase
{
    private function schemaReady(): bool
    {
        try {
            return Schema::hasTable('restaurants')
                && Schema::hasTable('managers')
                && Schema::hasTable('admins')
                && Schema::hasTable('subscriptions')
                && Schema::hasTable('subscription_plans')
                && Schema::hasTable('menu_items')
                && Schema::hasTable('categories')
                && Schema::hasTable('sections');
        } catch (\Throwable) {
            return false;
        }
    }

    public function test_admin_create_restaurant_always_starts_a_trial(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        $plan = SubscriptionPlan::query()->where('is_active', 1)->orderBy('display_order')->first();
        if (! $admin || ! $plan) {
            $this->markTestSkipped('Staging admin or subscription plan not available.');
        }

        $suffix = uniqid();
        $slug = 'trial-rest-'.$suffix;

        $this->actingAs($admin, 'admin')
            ->post(route('admin.restaurants.store'), [
                'name' => 'Trial Restaurant '.$suffix,
                'slug' => $slug,
                'manager_username' => 'mgr'.$suffix,
                'manager_email' => 'mgr'.$suffix.'@example.com',
                'manager_password' => 'password12',
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.restaurants.index'));

        $restaurant = Restaurant::query()->where('slug', $slug)->first();
        $this->assertNotNull($restaurant);

        $subscription = Subscription::query()->where('restaurant_id', $restaurant->id)->first();
        $this->assertNotNull($subscription);
        $this->assertSame('trial', $subscription->status);
        $this->assertNotNull($subscription->trial_ends_at);
        $this->assertTrue($subscription->trial_ends_at->isFuture());
    }

    public function test_manager_billing_subscribe_without_subscription_goes_to_checkout(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $plan = SubscriptionPlan::query()->where('is_active', 1)->orderBy('display_order')->first();
        if (! $plan) {
            $this->markTestSkipped('No subscription plans in database.');
        }

        $suffix = uniqid();
        $restaurant = Restaurant::create([
            'name' => 'No Sub '.$suffix,
            'slug' => 'no-sub-'.$suffix,
            'email' => 'nosub'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        $manager = Manager::create([
            'username' => 'nosub'.$suffix,
            'email' => 'nosub'.$suffix.'@example.com',
            'password_hash' => bcrypt('password'),
            'restaurant_id' => $restaurant->id,
        ]);

        $this->actingAs($manager, 'manager')
            ->withSession(['restaurant_id' => $restaurant->id])
            ->post(route('manager.billing.index'), [
                'action' => 'schedule_change',
                'target_plan_id' => $plan->id,
                'target_cycle' => 'monthly',
            ])
            ->assertRedirect(route('manager.billing.checkout', [
                'plan' => $plan->slug,
                'cycle' => 'monthly',
            ]))
            ->assertSessionMissing('error');
    }

    public function test_unchecking_available_marks_menu_item_unavailable(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $planId = SubscriptionPlan::query()->where('is_active', 1)->value('id');
        if (! $planId) {
            $this->markTestSkipped('No subscription plans in database.');
        }

        $suffix = uniqid();
        $restaurant = Restaurant::create([
            'name' => 'Avail '.$suffix,
            'slug' => 'avail-'.$suffix,
            'email' => 'avail'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        Subscription::forceCreate([
            'restaurant_id' => $restaurant->id,
            'plan_id' => (int) $planId,
            'billing_cycle' => 'monthly',
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(7),
        ]);
        $manager = Manager::create([
            'username' => 'avail'.$suffix,
            'email' => 'avail'.$suffix.'@example.com',
            'password_hash' => bcrypt('password'),
            'restaurant_id' => $restaurant->id,
        ]);

        $sectionId = DB::table('sections')->insertGetId([
            'restaurant_id' => $restaurant->id,
            'name' => 'Mains',
            'slug' => 'mains-'.$suffix,
            'display_order' => 0,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $category = Category::create([
            'restaurant_id' => $restaurant->id,
            'section_id' => $sectionId,
            'name' => 'Drinks',
            'slug' => 'drinks-'.$suffix,
            'display_order' => 0,
            'is_active' => 1,
        ]);
        $item = MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $category->id,
            'name' => 'Cola',
            'slug' => 'cola-'.$suffix,
            'price' => 5,
            'display_order' => 0,
            'is_available' => true,
        ]);

        $this->actingAs($manager, 'manager')
            ->withSession(['restaurant_id' => $restaurant->id])
            ->put(route('manager.menu-items.update', $item), [
                'name' => 'Cola',
                'category_id' => $category->id,
                'price' => 5,
                'display_order' => 0,
                'is_available' => '0',
            ])
            ->assertRedirect();

        $this->assertFalse($item->fresh()->is_available);
    }
}
