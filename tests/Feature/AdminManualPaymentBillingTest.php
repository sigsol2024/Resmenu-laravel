<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminManualPaymentBillingTest extends TestCase
{
    private function schemaReady(): bool
    {
        try {
            return Schema::hasTable('restaurants')
                && Schema::hasTable('admins')
                && Schema::hasTable('managers')
                && Schema::hasTable('subscriptions')
                && Schema::hasTable('subscription_plans')
                && Schema::hasTable('payments');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @return array{admin:Admin, restaurant:Restaurant, basic_id:int, pro_id:int}|null */
    private function seedContext(): ?array
    {
        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            return null;
        }

        $suffix = uniqid();
        $basicId = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'Manual Basic '.$suffix,
            'slug' => 'manual-basic-'.$suffix,
            'monthly_price' => 10000,
            'annual_price' => 120000,
            'max_categories' => 10,
            'max_menu_items' => 50,
            'max_qr_styles' => 3,
            'max_templates' => 3,
            'is_active' => 1,
            'display_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $proId = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'Manual Pro '.$suffix,
            'slug' => 'manual-pro-'.$suffix,
            'monthly_price' => 20000,
            'annual_price' => 240000,
            'max_categories' => 50,
            'max_menu_items' => 200,
            'max_qr_styles' => 5,
            'max_templates' => 5,
            'is_active' => 1,
            'display_order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $restaurant = Restaurant::create([
            'name' => 'Manual Pay Cafe '.$suffix,
            'slug' => 'manual-pay-'.$suffix,
            'email' => 'manual-pay-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        Manager::create([
            'username' => 'manual-mgr-'.$suffix,
            'email' => 'manual-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        return [
            'admin' => $admin,
            'restaurant' => $restaurant,
            'basic_id' => $basicId,
            'pro_id' => $proId,
        ];
    }

    public function test_quote_endpoint_returns_residual_for_active_upgrade(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }
        $ctx = $this->seedContext();
        if (! $ctx) {
            $this->markTestSkipped('Staging admin not available.');
        }

        Carbon::setTestNow(Carbon::parse('2026-07-01 12:00:00'));
        Subscription::forceCreate([
            'restaurant_id' => $ctx['restaurant']->id,
            'plan_id' => $ctx['basic_id'],
            'billing_cycle' => 'annual',
            'status' => 'active',
            'current_period_start' => Carbon::parse('2026-01-01 12:00:00'),
            'current_period_end' => Carbon::parse('2027-01-01 12:00:00'),
        ]);

        $this->actingAs($ctx['admin'], 'admin')
            ->getJson(route('admin.payments.quote', [
                'restaurant_id' => $ctx['restaurant']->id,
                'plan_id' => $ctx['pro_id'],
                'billing_cycle' => 'annual',
            ]))
            ->assertOk()
            ->assertJsonPath('outcome', 'charge')
            ->assertJsonPath('pricing_mode', 'residual_difference');

        Carbon::setTestNow();
    }

    public function test_successful_manual_payment_upgrades_and_preserves_period(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }
        $ctx = $this->seedContext();
        if (! $ctx) {
            $this->markTestSkipped('Staging admin not available.');
        }

        Carbon::setTestNow(Carbon::parse('2026-07-01 12:00:00'));
        $periodStart = Carbon::parse('2026-01-01 12:00:00');
        $periodEnd = Carbon::parse('2027-01-01 12:00:00');
        $subscription = Subscription::forceCreate([
            'restaurant_id' => $ctx['restaurant']->id,
            'plan_id' => $ctx['basic_id'],
            'billing_cycle' => 'annual',
            'status' => 'active',
            'current_period_start' => $periodStart,
            'current_period_end' => $periodEnd,
        ]);

        $this->actingAs($ctx['admin'], 'admin')
            ->post(route('admin.payments.store'), [
                'action' => 'create_manual',
                'restaurant_id' => $ctx['restaurant']->id,
                'plan_id' => $ctx['pro_id'],
                'billing_cycle' => 'annual',
                'status' => 'success',
                'amount' => 1, // tamper attempt — ignored
            ])
            ->assertRedirect();

        $subscription->refresh();
        $this->assertSame($ctx['pro_id'], (int) $subscription->plan_id);
        $this->assertTrue($subscription->current_period_start->equalTo($periodStart));
        $this->assertTrue($subscription->current_period_end->equalTo($periodEnd));

        $payment = DB::table('payments')->where('restaurant_id', $ctx['restaurant']->id)->orderByDesc('id')->first();
        $this->assertNotNull($payment);
        $this->assertSame('success', $payment->status);
        $this->assertSame('manual', $payment->payment_gateway);
        $this->assertEqualsWithDelta(60000.0, (float) $payment->amount, 1.0);
        $this->assertSame($ctx['pro_id'], (int) $payment->plan_id);

        Carbon::setTestNow();
    }

    public function test_pending_manual_payment_does_not_change_plan(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }
        $ctx = $this->seedContext();
        if (! $ctx) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $subscription = Subscription::forceCreate([
            'restaurant_id' => $ctx['restaurant']->id,
            'plan_id' => $ctx['basic_id'],
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => now()->subDays(5),
            'current_period_end' => now()->addDays(25),
        ]);

        $this->actingAs($ctx['admin'], 'admin')
            ->post(route('admin.payments.store'), [
                'action' => 'create_manual',
                'restaurant_id' => $ctx['restaurant']->id,
                'plan_id' => $ctx['pro_id'],
                'billing_cycle' => 'monthly',
                'status' => 'pending',
            ])
            ->assertRedirect();

        $subscription->refresh();
        $this->assertSame($ctx['basic_id'], (int) $subscription->plan_id);
        $payment = DB::table('payments')->where('restaurant_id', $ctx['restaurant']->id)->orderByDesc('id')->first();
        $this->assertSame('pending', $payment->status);
    }

    public function test_same_plan_manual_payment_rejected(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }
        $ctx = $this->seedContext();
        if (! $ctx) {
            $this->markTestSkipped('Staging admin not available.');
        }

        Subscription::forceCreate([
            'restaurant_id' => $ctx['restaurant']->id,
            'plan_id' => $ctx['basic_id'],
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => now()->subDay(),
            'current_period_end' => now()->addMonth(),
        ]);

        $this->actingAs($ctx['admin'], 'admin')
            ->from(route('admin.payments.index'))
            ->post(route('admin.payments.store'), [
                'action' => 'create_manual',
                'restaurant_id' => $ctx['restaurant']->id,
                'plan_id' => $ctx['basic_id'],
                'billing_cycle' => 'monthly',
                'status' => 'success',
            ])
            ->assertRedirect(route('admin.payments.index'))
            ->assertSessionHas('error');

        $this->assertSame(0, DB::table('payments')->where('restaurant_id', $ctx['restaurant']->id)->count());
    }

    public function test_downgrade_via_manual_form_schedules_without_payment(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }
        $ctx = $this->seedContext();
        if (! $ctx) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $subscription = Subscription::forceCreate([
            'restaurant_id' => $ctx['restaurant']->id,
            'plan_id' => $ctx['pro_id'],
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => now()->subDays(5),
            'current_period_end' => now()->addDays(25),
        ]);

        $this->actingAs($ctx['admin'], 'admin')
            ->post(route('admin.payments.store'), [
                'action' => 'create_manual',
                'restaurant_id' => $ctx['restaurant']->id,
                'plan_id' => $ctx['basic_id'],
                'billing_cycle' => 'monthly',
                'status' => 'success',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $subscription->refresh();
        $this->assertSame($ctx['pro_id'], (int) $subscription->plan_id);
        $this->assertSame(0, DB::table('payments')->where('restaurant_id', $ctx['restaurant']->id)->count());
        $this->assertNotNull(
            DB::table('subscription_change_requests')
                ->where('subscription_id', $subscription->id)
                ->where('status', 'pending')
                ->first()
        );
    }

    public function test_change_plan_action_is_rejected(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }
        $ctx = $this->seedContext();
        if (! $ctx) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $subscription = Subscription::forceCreate([
            'restaurant_id' => $ctx['restaurant']->id,
            'plan_id' => $ctx['basic_id'],
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ]);

        $this->actingAs($ctx['admin'], 'admin')
            ->from(route('admin.subscriptions.index'))
            ->patch(route('admin.subscriptions.update', $subscription), [
                'action' => 'change_plan',
                'new_plan_id' => $ctx['pro_id'],
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $subscription->refresh();
        $this->assertSame($ctx['basic_id'], (int) $subscription->plan_id);
    }

    public function test_subscriptions_page_has_record_payment_not_change_plan(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }
        $ctx = $this->seedContext();
        if (! $ctx) {
            $this->markTestSkipped('Staging admin not available.');
        }

        Subscription::forceCreate([
            'restaurant_id' => $ctx['restaurant']->id,
            'plan_id' => $ctx['basic_id'],
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ]);

        $html = $this->actingAs($ctx['admin'], 'admin')
            ->get(route('admin.subscriptions.index'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('Record Payment', $html);
        $this->assertStringNotContainsString('Change Plan', $html);
        $this->assertStringContainsString('manual=1', $html);
        $this->assertStringContainsString('restaurant_id='.$ctx['restaurant']->id, $html);
    }

    public function test_payments_page_deep_link_opens_manual_context(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }
        $ctx = $this->seedContext();
        if (! $ctx) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $html = $this->actingAs($ctx['admin'], 'admin')
            ->get(route('admin.payments.index', [
                'manual' => 1,
                'restaurant_id' => $ctx['restaurant']->id,
            ]))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('manualPaymentModal', $html);
        $this->assertStringContainsString('name="plan_id"', $html);
        $this->assertStringNotContainsString('name="subscription_id"', $html);
        $this->assertStringNotContainsString('name="amount"', $html);
        $this->assertStringContainsString('openManual = true', $html);
    }
}
