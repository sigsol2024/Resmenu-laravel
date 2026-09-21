<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Manager;
use App\Support\ApiJsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SecurityRemediationTest extends TestCase
{
    private function tablesAvailable(array $tables): bool
    {
        try {
            foreach ($tables as $table) {
                if (! Schema::hasTable($table)) {
                    return false;
                }
            }

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    public function test_cors_reflects_allowlisted_origin_only(): void
    {
        config(['resmenu.cors_allowed_origins' => 'https://resmenu.net,https://www.resmenu.net']);
        config(['cors.allowed_origins' => ['https://resmenu.net', 'https://www.resmenu.net']]);

        $allowed = $this->withHeaders(['Origin' => 'https://resmenu.net'])
            ->options('/api/leads');
        $allowed->assertStatus(204);
        $this->assertSame('https://resmenu.net', $allowed->headers->get('Access-Control-Allow-Origin'));

        $denied = $this->withHeaders(['Origin' => 'https://evil.example'])
            ->options('/api/leads');
        $denied->assertStatus(204);
        $this->assertNull($denied->headers->get('Access-Control-Allow-Origin'));

        $this->assertNotContains('*', ApiJsonResponse::allowedOrigins());
    }

    public function test_lead_honeypot_returns_benign_success(): void
    {
        $response = $this->postJson('/api/leads', [
            'email' => 'bot@example.com',
            'source' => 'homepage_popup',
            'website' => 'http://spam.test',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
    }

    public function test_lead_rejects_registration_source_from_public_api(): void
    {
        $response = $this->postJson('/api/leads', [
            'email' => 'human@example.com',
            'source' => 'registration',
            'marketing_consent' => true,
        ]);

        $response->assertStatus(422);
    }

    public function test_is_super_admin_not_mass_assignable(): void
    {
        if (! $this->tablesAvailable(['admins']) || ! Schema::hasColumn('admins', 'is_super_admin')) {
            $this->markTestSkipped('admins table unavailable.');
        }

        $username = 'sa_mass_'.uniqid();
        $id = DB::table('admins')->insertGetId([
            'username' => $username,
            'email' => $username.'@example.com',
            'password_hash' => bcrypt('password12345'),
            'is_super_admin' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin = Admin::findOrFail($id);
        $admin->fill([
            'is_super_admin' => 1,
            'is_active' => 0,
            'can_crm' => 1,
            'username' => $username,
        ]);
        $admin->save();
        $admin->refresh();

        $this->assertFalse((bool) $admin->is_super_admin);
        if (Schema::hasColumn('admins', 'is_active')) {
            $this->assertTrue((bool) $admin->is_active);
        }
        if (Schema::hasColumn('admins', 'can_crm')) {
            $this->assertFalse((bool) $admin->can_crm);
        }

        DB::table('admins')->where('id', $id)->delete();
    }

    public function test_email_verified_at_not_mass_assignable_on_manager(): void
    {
        if (! $this->tablesAvailable(['managers', 'restaurants'])) {
            $this->markTestSkipped('Required tables not available.');
        }

        $slug = 'mass-'.uniqid();
        $restaurantId = DB::table('restaurants')->insertGetId([
            'name' => 'Mass Test',
            'slug' => $slug,
            'email' => $slug.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $manager = Manager::create([
            'username' => 'mass_'.uniqid(),
            'email' => 'mass-'.uniqid().'@example.com',
            'password_hash' => bcrypt('password12345'),
            'restaurant_id' => $restaurantId,
            'email_verified_at' => now(),
        ]);

        $this->assertNull($manager->fresh()->email_verified_at);

        DB::table('managers')->where('id', $manager->id)->delete();
        DB::table('restaurants')->where('id', $restaurantId)->delete();
    }

    public function test_unverified_manager_cannot_post_customization(): void
    {
        if (! $this->tablesAvailable(['managers', 'restaurants'])) {
            $this->markTestSkipped('Required tables not available.');
        }

        $slug = 'cust-'.uniqid();
        $restaurantId = DB::table('restaurants')->insertGetId([
            'name' => 'Cust Gate',
            'slug' => $slug,
            'email' => $slug.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
            'last_activity_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managerId = DB::table('managers')->insertGetId([
            'username' => 'cust_'.uniqid(),
            'email' => 'cust-'.uniqid().'@example.com',
            'password_hash' => bcrypt('password12345'),
            'restaurant_id' => $restaurantId,
            'email_verified_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            app(\App\Services\SubscriptionService::class)->startTrialForRestaurant($restaurantId);
        } catch (\Throwable) {
        }

        $manager = Manager::findOrFail($managerId);
        $response = $this->actingAs($manager, 'manager')
            ->post(route('manager.customization.save'), [
                'action' => 'save_customization',
                'primary_color' => '#111111',
            ]);

        $response->assertRedirect(route('manager.dashboard'));
    }

    public function test_non_super_admin_crm_routes_forbidden(): void
    {
        if (! $this->tablesAvailable(['admins']) || ! Schema::hasColumn('admins', 'is_super_admin')) {
            $this->markTestSkipped('admins table unavailable.');
        }

        $username = 'nsa_'.uniqid();
        $id = DB::table('admins')->insertGetId([
            'username' => $username,
            'email' => $username.'@example.com',
            'password_hash' => bcrypt('password12345'),
            'is_super_admin' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin = Admin::findOrFail($id);
        $response = $this->actingAs($admin, 'admin')->get(route('admin.crm.index'));
        $response->assertStatus(403);

        DB::table('admins')->where('id', $id)->delete();
    }
}
