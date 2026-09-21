<?php

namespace Tests\Feature;

use App\Models\Manager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ManagerEmailVerifiedMenuGateTest extends TestCase
{
    private function tablesAvailable(): bool
    {
        try {
            return Schema::hasTable('managers') && Schema::hasTable('restaurants');
        } catch (\Throwable) {
            return false;
        }
    }

    public function test_unverified_manager_cannot_open_menu_item_create(): void
    {
        if (! $this->tablesAvailable()) {
            $this->markTestSkipped('Required tables not available.');
        }

        [$manager] = $this->makeManager(false);

        $response = $this->actingAs($manager, 'manager')
            ->get(route('manager.menu-items.create'));

        $response->assertRedirect(route('manager.dashboard'));
    }

    public function test_verified_manager_can_open_menu_item_create(): void
    {
        if (! $this->tablesAvailable()) {
            $this->markTestSkipped('Required tables not available.');
        }

        [$manager] = $this->makeManager(true);

        $response = $this->actingAs($manager, 'manager')
            ->get(route('manager.menu-items.create'));

        // May redirect to billing if subscription inactive — either 200 or billing redirect is OK for gate test;
        // must NOT be the email-verify dashboard redirect with verify flash only.
        $this->assertFalse(
            $response->isRedirect() && $response->headers->get('Location') === route('manager.dashboard')
            && session('error') === 'Verify your email to manage your menu. Use Resend on the banner if needed.'
        );
    }

    /** @return array{0: Manager, 1: int} */
    private function makeManager(bool $verified): array
    {
        $slug = 'gate-'.uniqid();
        $restaurantId = DB::table('restaurants')->insertGetId([
            'name' => 'Gate Test',
            'slug' => $slug,
            'email' => $slug.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
            'last_activity_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managerId = DB::table('managers')->insertGetId([
            'username' => 'gate_'.uniqid(),
            'email' => 'gate-'.uniqid().'@example.com',
            'password_hash' => bcrypt('password12345'),
            'restaurant_id' => $restaurantId,
            'email_verified_at' => $verified ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Start trial so subscription middleware does not block first.
        try {
            if (Schema::hasTable('subscriptions') || method_exists(\App\Services\SubscriptionService::class, 'startTrialForRestaurant')) {
                app(\App\Services\SubscriptionService::class)->startTrialForRestaurant($restaurantId);
            }
        } catch (\Throwable) {
        }

        return [Manager::findOrFail($managerId), $restaurantId];
    }
}
