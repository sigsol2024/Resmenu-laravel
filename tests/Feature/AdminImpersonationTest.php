<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Manager;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminImpersonationTest extends TestCase
{
    private function schemaReady(): bool
    {
        try {
            return Schema::hasTable('restaurants')
                && Schema::hasTable('managers')
                && Schema::hasTable('admins');
        } catch (\Throwable) {
            return false;
        }
    }

    public function test_admin_can_impersonate_manager_and_leave(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $suffix = uniqid();
        $restaurant = Restaurant::create([
            'name' => 'Impersonate Cafe '.$suffix,
            'slug' => 'impersonate-cafe-'.$suffix,
            'email' => 'impersonate'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        $manager = Manager::create([
            'username' => 'imp-mgr-'.$suffix,
            'email' => 'imp-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.restaurants.impersonate', $restaurant))
            ->assertRedirect(route('manager.dashboard'));

        $this->assertTrue((bool) session('impersonating'));
        $this->assertSame((int) $admin->id, (int) session('impersonator_admin_id'));
        $this->assertAuthenticatedAs($manager, 'manager');
        $this->assertGuest('admin');

        $this->post(route('impersonation.leave'))
            ->assertRedirect(route('admin.restaurants.index'));

        $this->assertFalse((bool) session('impersonating'));
        $this->assertNull(session('impersonator_admin_id'));
        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertGuest('manager');
    }

    public function test_impersonate_requires_manager_account(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $suffix = uniqid();
        $restaurant = Restaurant::create([
            'name' => 'No Manager Cafe '.$suffix,
            'slug' => 'no-manager-cafe-'.$suffix,
            'email' => 'no-mgr-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        $this->actingAs($admin, 'admin')
            ->from(route('admin.restaurants.index'))
            ->post(route('admin.restaurants.impersonate', $restaurant))
            ->assertRedirect(route('admin.restaurants.index', ['edit' => $restaurant->id]))
            ->assertSessionHasErrors('impersonation');

        $this->assertGuest('manager');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_impersonate_rejects_suspended_restaurant(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $suffix = uniqid();
        $restaurant = Restaurant::create([
            'name' => 'Suspended Cafe '.$suffix,
            'slug' => 'suspended-cafe-'.$suffix,
            'email' => 'susp-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
            'suspended_at' => now(),
            'suspension_reason' => 'admin',
        ]);
        Manager::create([
            'username' => 'susp-mgr-'.$suffix,
            'email' => 'susp-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->from(route('admin.restaurants.index'))
            ->post(route('admin.restaurants.impersonate', $restaurant))
            ->assertRedirect(route('admin.restaurants.index'))
            ->assertSessionHasErrors('impersonation');

        $this->assertGuest('manager');
    }

    public function test_nested_impersonation_is_rejected(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $suffix = uniqid();
        $restaurant = Restaurant::create([
            'name' => 'Nested Cafe '.$suffix,
            'slug' => 'nested-cafe-'.$suffix,
            'email' => 'nested-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        Manager::create([
            'username' => 'nested-mgr-'.$suffix,
            'email' => 'nested-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->withSession(['impersonating' => true, 'impersonator_admin_id' => $admin->id])
            ->post(route('admin.restaurants.impersonate', $restaurant))
            ->assertRedirect(route('admin.restaurants.index'))
            ->assertSessionHasErrors('impersonation');
    }

    public function test_subscription_bypass_while_impersonating_allows_sections(): void
    {
        if (! $this->schemaReady() || ! Schema::hasTable('sections')) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $suffix = uniqid();
        $restaurant = Restaurant::create([
            'name' => 'No Sub Cafe '.$suffix,
            'slug' => 'no-sub-cafe-'.$suffix,
            'email' => 'nosub-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        $manager = Manager::create([
            'username' => 'nosub-mgr-'.$suffix,
            'email' => 'nosub-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.restaurants.impersonate', $restaurant))
            ->assertRedirect(route('manager.dashboard'));

        $this->get(route('manager.sections.index'))->assertOk();
        $this->assertAuthenticatedAs($manager, 'manager');
    }

    public function test_legacy_hub_url_redirects_to_restaurants_index(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $restaurant = Restaurant::query()->whereNull('suspended_at')->orderByDesc('id')->first();
        if (! $restaurant) {
            $suffix = uniqid();
            $restaurant = Restaurant::create([
                'name' => 'Hub Redirect Cafe '.$suffix,
                'slug' => 'hub-redirect-'.$suffix,
                'email' => 'hub-'.$suffix.'@example.com',
                'is_active' => 1,
                'template_id' => 4,
            ]);
        }

        $this->actingAs($admin, 'admin')
            ->get(route('admin.restaurants.hub', $restaurant))
            ->assertRedirect(route('admin.restaurants.index'))
            ->assertSessionHas('success');
    }

    public function test_logout_while_impersonating_restores_admin(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $suffix = uniqid();
        $restaurant = Restaurant::create([
            'name' => 'Logout Cafe '.$suffix,
            'slug' => 'logout-cafe-'.$suffix,
            'email' => 'logout-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        Manager::create([
            'username' => 'logout-mgr-'.$suffix,
            'email' => 'logout-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.restaurants.impersonate', $restaurant))
            ->assertRedirect(route('manager.dashboard'));

        $this->post(route('logout'))
            ->assertRedirect(route('admin.restaurants.index'));

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertGuest('manager');
        $this->assertFalse((bool) session('impersonating'));
    }
}
