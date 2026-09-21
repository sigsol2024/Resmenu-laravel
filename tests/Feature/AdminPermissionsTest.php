<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Services\Admin\AdminAccountService;
use App\View\Composers\AdminLayoutComposer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Tests\TestCase;

class AdminPermissionsTest extends TestCase
{
    /** @var list<int> */
    private array $createdAdminIds = [];

    protected function tearDown(): void
    {
        if ($this->createdAdminIds !== [] && Schema::hasTable('admins')) {
            DB::table('admins')->whereIn('id', $this->createdAdminIds)->delete();
        }

        parent::tearDown();
    }

    private function schemaReady(): bool
    {
        try {
            return Schema::hasTable('admins')
                && Schema::hasColumn('admins', 'is_super_admin')
                && Schema::hasColumn('admins', 'is_active')
                && Schema::hasColumn('admins', 'can_crm');
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * @param  array<string, mixed>  $attrs
     */
    private function makeAdmin(array $attrs = []): Admin
    {
        $suffix = uniqid();
        $row = array_merge([
            'username' => 'adm_'.$suffix,
            'email' => 'adm_'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'is_super_admin' => 0,
            'is_active' => 1,
            'can_subscription_plans' => 0,
            'can_subscriptions' => 0,
            'can_payments' => 0,
            'can_payment_settings' => 0,
            'can_templates' => 0,
            'can_qr_templates' => 0,
            'can_settings' => 0,
            'can_crm' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ], $attrs);

        $id = DB::table('admins')->insertGetId($row);
        $this->createdAdminIds[] = $id;

        return Admin::findOrFail($id);
    }

    public function test_super_admin_can_access_modules_and_admin_management(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $super = $this->makeAdmin(['is_super_admin' => 1]);

        $this->actingAs($super, 'admin')->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($super, 'admin')->get(route('admin.crm.index'))->assertOk();
        $this->actingAs($super, 'admin')->get(route('admin.settings.index'))->assertOk();
        $this->actingAs($super, 'admin')->get(route('admin.admins.index'))->assertOk();
        $this->actingAs($super, 'admin')->get(route('admin.profile.show'))->assertOk();
    }

    public function test_super_admin_can_create_regular_and_super_admins_with_permissions(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $super = $this->makeAdmin(['is_super_admin' => 1]);
        $suffix = uniqid();

        $this->actingAs($super, 'admin')
            ->post(route('admin.admins.store'), [
                'username' => 'reg_'.$suffix,
                'email' => 'reg_'.$suffix.'@example.com',
                'password' => 'password12',
                'role' => 'regular',
                'is_active' => '1',
                'permissions' => [
                    'crm' => '1',
                    'settings' => '1',
                ],
            ])
            ->assertRedirect(route('admin.admins.index'));

        $regular = Admin::query()->where('username', 'reg_'.$suffix)->first();
        $this->assertNotNull($regular);
        $this->createdAdminIds[] = (int) $regular->id;
        $this->assertFalse($regular->isSuperAdmin());
        $this->assertTrue($regular->hasPermission('crm'));
        $this->assertTrue($regular->hasPermission('settings'));
        $this->assertFalse($regular->hasPermission('payments'));

        $this->actingAs($super, 'admin')
            ->post(route('admin.admins.store'), [
                'username' => 'sup_'.$suffix,
                'email' => 'sup_'.$suffix.'@example.com',
                'password' => 'password12',
                'role' => 'super',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.admins.index'));

        $createdSuper = Admin::query()->where('username', 'sup_'.$suffix)->first();
        $this->assertNotNull($createdSuper);
        $this->createdAdminIds[] = (int) $createdSuper->id;
        $this->assertTrue($createdSuper->isSuperAdmin());
        $this->assertTrue($createdSuper->hasPermission('crm'));
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function permissionGetRouteProvider(): array
    {
        return [
            'subscription_plans' => ['subscription_plans', 'admin.subscription-plans.index'],
            'subscriptions' => ['subscriptions', 'admin.subscriptions.index'],
            'payments' => ['payments', 'admin.payments.index'],
            'payment_settings' => ['payment_settings', 'admin.payment-settings.index'],
            'templates' => ['templates', 'admin.templates.index'],
            'qr_templates' => ['qr_templates', 'admin.qr-templates.index'],
            'settings' => ['settings', 'admin.settings.index'],
            'crm' => ['crm', 'admin.crm.index'],
        ];
    }

    /**
     * Parameter-free mutation routes so middleware runs without model binding 404s.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function permissionPostRouteProvider(): array
    {
        return [
            'subscription_plans' => ['subscription_plans', 'admin.subscription-plans.store'],
            'subscriptions' => ['subscriptions', 'admin.subscriptions.store'],
            'payments' => ['payments', 'admin.payments.store'],
            'payment_settings' => ['payment_settings', 'admin.payment-settings.update'],
            'qr_templates' => ['qr_templates', 'admin.qr-templates.store'],
            'settings' => ['settings', 'admin.settings.index'],
            'crm' => ['crm', 'admin.crm.update'],
        ];
    }

    /**
     * @dataProvider permissionGetRouteProvider
     */
    public function test_regular_admin_permission_gates_get_routes(string $key, string $getRoute): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $column = Admin::PERMISSION_MAP[$key];
        $denied = $this->makeAdmin([$column => 0]);
        $allowed = $this->makeAdmin([$column => 1]);

        $this->actingAs($denied, 'admin')->get(route($getRoute))->assertForbidden();
        $this->actingAs($allowed, 'admin')->get(route($getRoute))->assertOk();
    }

    /**
     * @dataProvider permissionPostRouteProvider
     */
    public function test_regular_admin_permission_gates_post_routes(string $key, string $postRoute): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $column = Admin::PERMISSION_MAP[$key];
        $denied = $this->makeAdmin([$column => 0]);
        $allowed = $this->makeAdmin([$column => 1]);

        $deniedResponse = $this->actingAs($denied, 'admin')->post(route($postRoute), []);
        $this->assertSame(403, $deniedResponse->status());

        $allowedResponse = $this->actingAs($allowed, 'admin')->post(route($postRoute), []);
        $this->assertNotSame(403, $allowedResponse->status());
    }

    public function test_regular_admin_cannot_access_admin_management(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $regular = $this->makeAdmin(['can_settings' => 1]);

        $this->actingAs($regular, 'admin')->get(route('admin.admins.index'))->assertForbidden();
        $this->actingAs($regular, 'admin')->post(route('admin.admins.store'), [
            'username' => 'x_'.uniqid(),
            'email' => 'x_'.uniqid().'@example.com',
            'password' => 'password12',
            'role' => 'super',
            'is_active' => '1',
        ])->assertForbidden();
    }

    public function test_profile_ignores_privilege_escalation_payload(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $regular = $this->makeAdmin();

        $this->actingAs($regular, 'admin')
            ->put(route('admin.profile.update'), [
                'action' => 'update_profile',
                'username' => $regular->username,
                'email' => $regular->email,
                'is_super_admin' => 1,
                'is_active' => 1,
                'can_crm' => 1,
                'can_payments' => 1,
            ])
            ->assertRedirect();

        $regular->refresh();
        $this->assertFalse($regular->isSuperAdmin());
        $this->assertFalse($regular->hasPermission('crm'));
        $this->assertFalse($regular->hasPermission('payments'));
    }

    public function test_mass_assignment_rejects_privileged_fields(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $admin = $this->makeAdmin();
        $admin->fill([
            'is_super_admin' => 1,
            'is_active' => 0,
            'can_crm' => 1,
            'username' => $admin->username,
        ]);
        $admin->save();
        $admin->refresh();

        $this->assertFalse($admin->isSuperAdmin());
        $this->assertTrue($admin->isActive());
        $this->assertFalse($admin->hasPermission('crm'));
    }

    public function test_primary_super_admin_cannot_be_deleted_deactivated_or_demoted(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $primary = Admin::query()->orderBy('id')->first();
        if (! $primary) {
            $this->markTestSkipped('No primary admin available.');
        }

        // Ensure primary is super + active for this assertion without leaving DB dirty if already so.
        $wasSuper = $primary->isSuperAdmin();
        $wasActive = $primary->isActive();
        DB::table('admins')->where('id', $primary->id)->update([
            'is_super_admin' => 1,
            'is_active' => 1,
        ]);
        $primary->refresh();

        $actor = $this->makeAdmin(['is_super_admin' => 1]);
        $service = app(AdminAccountService::class);

        try {
            $service->delete($primary, $actor);
            $this->fail('Expected delete of primary to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        try {
            $service->update($primary, ['is_active' => false, 'is_super_admin' => true], $actor);
            $this->fail('Expected deactivate of primary to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        try {
            $service->update($primary, ['is_super_admin' => false, 'is_active' => true], $actor);
            $this->fail('Expected demote of primary to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        DB::table('admins')->where('id', $primary->id)->update([
            'is_super_admin' => $wasSuper ? 1 : 0,
            'is_active' => $wasActive ? 1 : 0,
        ]);
    }

    public function test_self_protection_blocks_delete_deactivate_demote(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $self = $this->makeAdmin(['is_super_admin' => 1]);
        // Ensure another active super exists so zero-super is not the blocking reason.
        $this->makeAdmin(['is_super_admin' => 1]);
        $service = app(AdminAccountService::class);

        try {
            $service->delete($self, $self);
            $this->fail('Expected self-delete to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        try {
            $service->update($self, ['is_active' => false, 'is_super_admin' => true], $self);
            $this->fail('Expected self-deactivate to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        try {
            $service->update($self, ['is_super_admin' => false, 'is_active' => true], $self);
            $this->fail('Expected self-demote to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }
    }

    public function test_cannot_leave_zero_active_super_admins(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $victim = $this->makeAdmin(['is_super_admin' => 1, 'is_active' => 1]);
        $actor = $this->makeAdmin(['is_super_admin' => 1, 'is_active' => 1]);

        // Count other active supers excluding our two test accounts.
        $otherActiveSupers = Admin::query()
            ->where('is_super_admin', true)
            ->where('is_active', true)
            ->whereNotIn('id', [$victim->id, $actor->id])
            ->count();

        if ($otherActiveSupers > 0) {
            $this->markTestSkipped('Shared DB has other active Super Admins; cannot isolate zero-super case safely.');
        }

        $service = app(AdminAccountService::class);

        // Demote victim while actor remains — should succeed.
        $service->update($victim, ['is_super_admin' => false, 'is_active' => true], $actor);
        $victim->refresh();
        $this->assertFalse($victim->isSuperAdmin());

        // Actor is now the sole active Super among remaining — cannot demote/deactivate self or be removed by self.
        try {
            $service->update($actor, ['is_super_admin' => false, 'is_active' => true], $actor);
            $this->fail('Expected demote of last super (self) to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        try {
            $service->update($actor, ['is_super_admin' => true, 'is_active' => false], $actor);
            $this->fail('Expected deactivate of last super (self) to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        // Re-promote victim so actor can attempt demoting the last remaining other... 
        // After self-blocks, promote victim again and demote actor leaving victim as last, then victim cannot be demoted by actor if actor is regular.
        DB::table('admins')->where('id', $victim->id)->update(['is_super_admin' => 1, 'is_active' => 1]);
        $victim->refresh();
        $service->update($actor, ['is_super_admin' => false, 'is_active' => true], $victim);
        $actor->refresh();
        $this->assertFalse($actor->isSuperAdmin());

        try {
            $service->update($victim, ['is_super_admin' => false, 'is_active' => true], $victim);
            $this->fail('Expected demote of sole remaining active Super to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        $victim->refresh();
        $this->assertTrue($victim->isSuperAdmin());
        $this->assertTrue($victim->isActive());
    }

    public function test_regular_actor_cannot_invoke_admin_account_service(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $regular = $this->makeAdmin(['is_super_admin' => 0]);
        $target = $this->makeAdmin(['is_super_admin' => 0]);
        $service = app(AdminAccountService::class);

        try {
            $service->create([
                'username' => 'blocked_'.uniqid(),
                'email' => 'blocked_'.uniqid().'@example.com',
                'password' => 'password12',
                'is_super_admin' => true,
                'is_active' => true,
            ], $regular);
            $this->fail('Expected create by Regular to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        try {
            $service->update($target, ['is_super_admin' => true], $regular);
            $this->fail('Expected update by Regular to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        try {
            $service->delete($target, $regular);
            $this->fail('Expected delete by Regular to fail.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('admin', $e->errors());
        }

        $target->refresh();
        $this->assertFalse($target->isSuperAdmin());
    }

    public function test_sequential_demote_one_of_two_supers_leaves_one_active(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $a = $this->makeAdmin(['is_super_admin' => 1, 'is_active' => 1]);
        $b = $this->makeAdmin(['is_super_admin' => 1, 'is_active' => 1]);
        $service = app(AdminAccountService::class);

        $service->update($a, ['is_super_admin' => false, 'is_active' => true], $b);
        $a->refresh();
        $b->refresh();

        $this->assertFalse($a->isSuperAdmin());
        $this->assertTrue($b->isSuperAdmin());
        $this->assertTrue($b->isActive());
    }

    public function test_concurrent_mutual_demotion_cannot_leave_zero_active_supers(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $a = $this->makeAdmin(['is_super_admin' => 1, 'is_active' => 1]);
        $b = $this->makeAdmin(['is_super_admin' => 1, 'is_active' => 1]);

        $otherActiveSupers = Admin::query()
            ->where('is_super_admin', true)
            ->where('is_active', true)
            ->whereNotIn('id', [$a->id, $b->id])
            ->count();

        if ($otherActiveSupers > 0) {
            $this->markTestSkipped('Shared DB has other active Super Admins; concurrency isolation not available.');
        }

        // Simulate the race: two overlapping transactions each try to demote the other.
        // With lockForUpdate, one must complete first; the second must fail if it would leave zero.
        $service = app(AdminAccountService::class);
        $errors = 0;
        $successes = 0;

        try {
            $service->update($a, ['is_super_admin' => false, 'is_active' => true], $b);
            $successes++;
        } catch (\Illuminate\Validation\ValidationException) {
            $errors++;
        }

        try {
            $service->update($b, ['is_super_admin' => false, 'is_active' => true], $a->fresh() ?? $a);
            $successes++;
        } catch (\Illuminate\Validation\ValidationException) {
            $errors++;
        }

        // After sequential "race" simulation: at least one active Super among A/B must remain
        // (second demote should fail because first already demoted one).
        $a->refresh();
        $b->refresh();
        $activeSupersLeft = ((int) $a->isSuperAdmin() && $a->isActive() ? 1 : 0)
            + ((int) $b->isSuperAdmin() && $b->isActive() ? 1 : 0);

        $this->assertGreaterThanOrEqual(1, $activeSupersLeft);
        $this->assertSame(1, $successes);
        $this->assertSame(1, $errors);
    }

    public function test_inactive_admin_cannot_login_or_access_protected_routes(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $inactive = $this->makeAdmin(['is_active' => 0, 'password_hash' => Hash::make('password12')]);

        $this->post(route('login'), [
            'username' => $inactive->username,
            'password' => 'password12',
        ])->assertSessionHasErrors('username');

        $this->actingAs($inactive, 'admin')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_profile_password_change_regenerates_session(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $admin = $this->makeAdmin(['password_hash' => Hash::make('password12')]);

        $this->actingAs($admin, 'admin');
        $before = session()->getId();

        $this->put(route('admin.profile.update'), [
            'action' => 'update_password',
            'current_password' => 'password12',
            'new_password' => 'password99',
            'new_password_confirmation' => 'password99',
        ])->assertRedirect();

        $this->assertNotSame($before, session()->getId());
        $admin->refresh();
        $this->assertTrue(Hash::check('password99', $admin->password_hash));
    }

    public function test_duplicate_profile_email_rejected(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $a = $this->makeAdmin();
        $b = $this->makeAdmin();

        $this->actingAs($a, 'admin')
            ->put(route('admin.profile.update'), [
                'action' => 'update_profile',
                'username' => $a->username,
                'email' => $b->email,
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_sidebar_filters_by_permission_and_shows_profile_for_all(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $super = $this->makeAdmin(['is_super_admin' => 1]);
        $regular = $this->makeAdmin(['can_crm' => 1, 'can_settings' => 0]);

        $this->actingAs($super, 'admin');
        $html = $this->get(route('admin.dashboard'))->assertOk()->getContent();
        $this->assertStringContainsString('Administrators', $html);
        $this->assertStringContainsString('CRM', $html);
        $this->assertStringContainsString('Profile', $html);
        $this->assertStringContainsString('Super Admin', $html);

        $this->actingAs($regular, 'admin');
        $html = $this->get(route('admin.dashboard'))->assertOk()->getContent();
        $this->assertStringContainsString('CRM', $html);
        $this->assertStringNotContainsString('>Administrators<', $html);
        $this->assertStringContainsString('Profile', $html);
        $this->assertStringContainsString('Administrator', $html);
        $this->assertStringNotContainsString('Payment Settings', $html);
    }

    public function test_unknown_permission_key_fails_closed(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $admin = $this->makeAdmin(['is_super_admin' => 0]);
        $this->assertFalse($admin->hasPermission('not_a_real_permission'));

        $super = $this->makeAdmin(['is_super_admin' => 1]);
        // Super still returns true only for known keys via hasPermission — unknown fails closed.
        $this->assertFalse($super->hasPermission('not_a_real_permission'));
    }

    public function test_templates_permission_gates_toggle_mutation(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        if (! Schema::hasTable('templates') || ! DB::table('templates')->exists()) {
            $this->markTestSkipped('No templates available for mutation permission test.');
        }

        $templateId = (int) DB::table('templates')->value('id');
        $denied = $this->makeAdmin(['can_templates' => 0]);
        $allowed = $this->makeAdmin(['can_templates' => 1]);

        $this->actingAs($denied, 'admin')
            ->post(route('admin.templates.toggle', $templateId))
            ->assertForbidden();

        $allowedResponse = $this->actingAs($allowed, 'admin')
            ->post(route('admin.templates.toggle', $templateId));
        $this->assertNotSame(403, $allowedResponse->status());
    }

    public function test_composer_nav_uses_has_permission(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Admin permission columns not available.');
        }

        $regular = $this->makeAdmin(['can_payments' => 1]);
        $this->actingAs($regular, 'admin');

        $composer = new AdminLayoutComposer;
        $view = \Mockery::mock(View::class);
        $captured = [];
        $view->shouldReceive('with')->once()->andReturnUsing(function ($data) use (&$captured, $view) {
            $captured = $data;

            return $view;
        });
        $composer->compose($view);

        $ids = collect($captured['layoutNavItems'])->pluck('id')->all();
        $this->assertContains('dashboard', $ids);
        $this->assertContains('restaurants', $ids);
        $this->assertContains('payments', $ids);
        $this->assertNotContains('admins', $ids);
        $this->assertNotContains('crm', $ids);
    }
}
