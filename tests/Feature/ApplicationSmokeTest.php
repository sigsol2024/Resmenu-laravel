<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ApplicationSmokeTest extends TestCase
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

    public function test_manager_login_and_dashboard(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $manager = Manager::query()->where('username', 'staging-manager')->first();
        if (! $manager) {
            $this->markTestSkipped('Run db:seed (05_staging_auth_bootstrap.sql) for smoke credentials.');
        }

        $this->post('/login', [
            'username' => 'staging-manager',
            'password' => 'password',
        ])->assertRedirect();

        $this->actingAs($manager, 'manager')
            ->get('/manager/dashboard')
            ->assertOk();
    }

    public function test_admin_login_and_dashboard(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Run db:seed (05_staging_auth_bootstrap.sql) for smoke credentials.');
        }

        $this->post('/login', [
            'username' => 'staging-admin',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard')
            ->assertOk();
    }

    public function test_public_menu_renders_for_seeded_restaurant(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $restaurant = Restaurant::query()->where('slug', 'the-mania-house')->where('is_active', 1)->first();
        if (! $restaurant) {
            $this->markTestSkipped('Bootstrap restaurant the-mania-house not found.');
        }

        $this->get('/restaurant/'.$restaurant->slug)->assertOk();
    }

    public function test_subscription_plans_exist_after_seed(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $this->assertTrue(SubscriptionPlan::query()->where('slug', 'basic')->exists());
        $this->assertTrue(SubscriptionPlan::query()->where('slug', 'enterprise')->exists());
    }

    public function test_login_page_renders(): void
    {
        $this->get('/')->assertOk();
    }
}
