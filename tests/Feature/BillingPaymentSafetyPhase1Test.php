<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Services\PaymentGatewayService;
use App\Services\SubscriptionPaymentLifecycleService;
use App\Services\SubscriptionService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Phase 1 billing-safety regression tests.
 * PHP may be unavailable in some environments — do not assume these passed without running them.
 */
class BillingPaymentSafetyPhase1Test extends TestCase
{
    private function schemaReady(): bool
    {
        try {
            return Schema::hasTable('restaurants')
                && Schema::hasTable('managers')
                && Schema::hasTable('admins')
                && Schema::hasTable('subscriptions')
                && Schema::hasTable('subscription_plans')
                && Schema::hasTable('payments')
                && Schema::hasTable('payment_settings');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @return array{restaurant:Restaurant, basic_id:int, pro_id:int, subscription:Subscription}|null */
    private function seedActiveAnnualBasicHalfway(): ?array
    {
        $suffix = uniqid();
        $basicId = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'Safe Basic '.$suffix,
            'slug' => 'safe-basic-'.$suffix,
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
            'name' => 'Safe Pro '.$suffix,
            'slug' => 'safe-pro-'.$suffix,
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
            'name' => 'Safe Cafe '.$suffix,
            'slug' => 'safe-cafe-'.$suffix,
            'email' => 'safe-cafe-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        Manager::create([
            'username' => 'safe-mgr-'.$suffix,
            'email' => 'safe-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        Carbon::setTestNow(Carbon::parse('2026-07-01 12:00:00'));
        $subscription = Subscription::forceCreate([
            'restaurant_id' => $restaurant->id,
            'plan_id' => $basicId,
            'billing_cycle' => 'annual',
            'status' => 'active',
            'current_period_start' => Carbon::parse('2026-01-01 12:00:00'),
            'current_period_end' => Carbon::parse('2027-01-01 12:00:00'),
        ]);

        return compact('restaurant', 'basicId', 'proId', 'subscription') + [
            'basic_id' => $basicId,
            'pro_id' => $proId,
        ];
    }

    private function ensurePaystackConfigured(): void
    {
        DB::table('payment_settings')->updateOrInsert(
            ['gateway' => 'paystack'],
            [
                'is_active' => 1,
                'test_mode' => 1,
                'public_key_test' => 'pk_test_x',
                'secret_key_test' => 'sk_test_x',
                'updated_at' => now(),
            ]
        );
    }

    public function test_gateway_success_monthly_to_annual_starts_new_period(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $suffix = uniqid();
        $basicId = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'Cyc Basic '.$suffix,
            'slug' => 'cyc-basic-'.$suffix,
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
            'name' => 'Cyc Pro '.$suffix,
            'slug' => 'cyc-pro-'.$suffix,
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
            'name' => 'Cyc Cafe '.$suffix,
            'slug' => 'cyc-cafe-'.$suffix,
            'email' => 'cyc-cafe-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        Manager::create([
            'username' => 'cyc-mgr-'.$suffix,
            'email' => 'cyc-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        Carbon::setTestNow(Carbon::parse('2026-06-15 10:00:00'));
        $oldEnd = Carbon::parse('2026-06-30 10:00:00');
        $subscription = Subscription::forceCreate([
            'restaurant_id' => $restaurant->id,
            'plan_id' => $basicId,
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => Carbon::parse('2026-05-30 10:00:00'),
            'current_period_end' => $oldEnd,
        ]);

        $this->ensurePaystackConfigured();
        Http::fake([
            'api.paystack.co/*' => Http::response([
                'status' => true,
                'data' => ['authorization_url' => 'https://checkout.paystack.com/test', 'reference' => 'PS_cyc'],
            ], 200),
        ]);

        $gateway = app(PaymentGatewayService::class);
        $init = $gateway->initializeSubscriptionPayment((int) $restaurant->id, $proId, 'annual', 'paystack');
        $this->assertArrayHasKey('redirect_url', $init);
        $payment = DB::table('payments')->where('restaurant_id', $restaurant->id)->orderByDesc('id')->first();
        $this->assertEquals(240000.0, (float) $payment->amount);

        $gateway->processPlatformPaystackSuccess([
            'reference' => $payment->transaction_reference,
            'amount' => (int) round(((float) $payment->amount) * 100),
            'currency' => 'NGN',
            'status' => 'success',
            'metadata' => ['plan_id' => $proId],
        ]);

        $subscription->refresh();
        $this->assertSame($proId, (int) $subscription->plan_id);
        $this->assertSame('annual', $subscription->billing_cycle);
        $this->assertTrue($subscription->current_period_end->greaterThan($oldEnd));
        Carbon::setTestNow();
    }

    public function test_gateway_success_annual_residual_preserves_period_dates(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedActiveAnnualBasicHalfway();
        $this->ensurePaystackConfigured();
        Http::fake([
            'api.paystack.co/*' => Http::response([
                'status' => true,
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/test',
                    'reference' => 'PS_safe_residual',
                ],
            ], 200),
        ]);

        $gateway = app(PaymentGatewayService::class);
        $init = $gateway->initializeSubscriptionPayment(
            (int) $ctx['restaurant']->id,
            $ctx['pro_id'],
            'annual',
            'paystack',
        );
        $this->assertArrayHasKey('redirect_url', $init);

        $payment = DB::table('payments')->where('restaurant_id', $ctx['restaurant']->id)->orderByDesc('id')->first();
        $this->assertNotNull($payment);
        $this->assertEqualsWithDelta(60000.0, (float) $payment->amount, 1.0);
        $intent = app(SubscriptionPaymentLifecycleService::class)->extractBillingIntent($payment);
        $this->assertSame('residual_difference', $intent['pricing_mode'] ?? null);
        $this->assertSame('preserve_period', $intent['apply_mode'] ?? null);

        $ok = $gateway->processPlatformPaystackSuccess([
            'reference' => $payment->transaction_reference,
            'amount' => (int) round(((float) $payment->amount) * 100),
            'currency' => 'NGN',
            'status' => 'success',
            'metadata' => ['plan_id' => $ctx['pro_id']],
        ]);
        $this->assertTrue($ok);

        $ctx['subscription']->refresh();
        $this->assertSame($ctx['pro_id'], (int) $ctx['subscription']->plan_id);
        $this->assertTrue($ctx['subscription']->current_period_start->equalTo(Carbon::parse('2026-01-01 12:00:00')));
        $this->assertTrue($ctx['subscription']->current_period_end->equalTo(Carbon::parse('2027-01-01 12:00:00')));

        Carbon::setTestNow();
    }

    public function test_gateway_success_monthly_residual_preserves_period(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $suffix = uniqid();
        $basicId = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'M Basic '.$suffix,
            'slug' => 'm-basic-'.$suffix,
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
            'name' => 'M Pro '.$suffix,
            'slug' => 'm-pro-'.$suffix,
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
            'name' => 'M Cafe '.$suffix,
            'slug' => 'm-cafe-'.$suffix,
            'email' => 'm-cafe-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        Manager::create([
            'username' => 'm-mgr-'.$suffix,
            'email' => 'm-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        Carbon::setTestNow(Carbon::parse('2026-06-16 12:00:00'));
        $periodStart = Carbon::parse('2026-06-01 12:00:00');
        $periodEnd = Carbon::parse('2026-07-01 12:00:00');
        $subscription = Subscription::forceCreate([
            'restaurant_id' => $restaurant->id,
            'plan_id' => $basicId,
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => $periodStart,
            'current_period_end' => $periodEnd,
        ]);

        $this->ensurePaystackConfigured();
        Http::fake([
            'api.paystack.co/*' => Http::response([
                'status' => true,
                'data' => ['authorization_url' => 'https://checkout.paystack.com/test', 'reference' => 'PS_m'],
            ], 200),
        ]);

        $gateway = app(PaymentGatewayService::class);
        $init = $gateway->initializeSubscriptionPayment((int) $restaurant->id, $proId, 'monthly', 'paystack');
        $this->assertArrayHasKey('redirect_url', $init);
        $payment = DB::table('payments')->where('restaurant_id', $restaurant->id)->orderByDesc('id')->first();

        $gateway->processPlatformPaystackSuccess([
            'reference' => $payment->transaction_reference,
            'amount' => (int) round(((float) $payment->amount) * 100),
            'currency' => 'NGN',
            'status' => 'success',
            'metadata' => ['plan_id' => $proId],
        ]);

        $subscription->refresh();
        $this->assertSame($proId, (int) $subscription->plan_id);
        $this->assertTrue($subscription->current_period_start->equalTo($periodStart));
        $this->assertTrue($subscription->current_period_end->equalTo($periodEnd));
        Carbon::setTestNow();
    }

    public function test_residual_payment_does_not_activate_after_expiry(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedActiveAnnualBasicHalfway();
        $this->ensurePaystackConfigured();
        Http::fake([
            'api.paystack.co/*' => Http::response([
                'status' => true,
                'data' => ['authorization_url' => 'https://checkout.paystack.com/test', 'reference' => 'PS_stale'],
            ], 200),
        ]);

        $gateway = app(PaymentGatewayService::class);
        $gateway->initializeSubscriptionPayment((int) $ctx['restaurant']->id, $ctx['pro_id'], 'annual', 'paystack');
        $payment = DB::table('payments')->where('restaurant_id', $ctx['restaurant']->id)->orderByDesc('id')->first();
        $this->assertEqualsWithDelta(60000.0, (float) $payment->amount, 1.0);

        // Expire subscription before callback.
        Carbon::setTestNow(Carbon::parse('2027-01-02 12:00:00'));
        $ctx['subscription']->forceFill([
            'status' => 'expired',
            'current_period_end' => Carbon::parse('2027-01-01 12:00:00'),
        ])->save();

        $gateway->processPlatformPaystackSuccess([
            'reference' => $payment->transaction_reference,
            'amount' => (int) round(((float) $payment->amount) * 100),
            'currency' => 'NGN',
            'status' => 'success',
            'metadata' => ['plan_id' => $ctx['pro_id']],
        ]);

        $ctx['subscription']->refresh();
        $this->assertSame($ctx['basic_id'], (int) $ctx['subscription']->plan_id, 'Must not upgrade underpaid after expiry');
        $this->assertSame('expired', $ctx['subscription']->status);
        $paid = DB::table('payments')->where('id', $payment->id)->first();
        $this->assertSame('success', $paid->status);

        Carbon::setTestNow();
    }

    public function test_blocked_live_outcome_does_not_mutate_subscription(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedActiveAnnualBasicHalfway();
        $lifecycle = app(SubscriptionPaymentLifecycleService::class);
        $intent = $lifecycle->buildBillingIntent(
            (int) $ctx['restaurant']->id,
            (int) $ctx['subscription']->id,
            $ctx['pro_id'],
            'monthly', // annual → monthly intent (would be blocked live)
            20000,
            'full',
            'activate',
        );

        $paymentId = (int) DB::table('payments')->insertGetId([
            'restaurant_id' => $ctx['restaurant']->id,
            'subscription_id' => $ctx['subscription']->id,
            'plan_id' => $ctx['pro_id'],
            'billing_cycle' => 'monthly',
            'amount' => 20000,
            'currency' => 'NGN',
            'payment_gateway' => 'paystack',
            'transaction_reference' => 'PS_blocked_'.uniqid(),
            'status' => 'pending',
            'gateway_response' => $lifecycle->encodeBillingIntentResponse(null, $intent),
            'created_at' => now(),
        ]);

        $payment = DB::table('payments')->where('id', $paymentId)->first();
        DB::table('payments')->where('id', $paymentId)->update(['status' => 'success', 'paid_at' => now()]);
        $payment = DB::table('payments')->where('id', $paymentId)->first();

        $ok = app(PaymentGatewayService::class)->fulfillSubscriptionPayment($payment);
        $this->assertTrue($ok);

        $ctx['subscription']->refresh();
        $this->assertSame($ctx['basic_id'], (int) $ctx['subscription']->plan_id);
        $this->assertSame('annual', $ctx['subscription']->billing_cycle);

        Carbon::setTestNow();
    }

    public function test_apply_quoted_plan_change_rejects_cross_restaurant_subscription(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $a = $this->seedActiveAnnualBasicHalfway();
        $b = $this->seedActiveAnnualBasicHalfway();

        $quote = app(SubscriptionService::class)->quotePlanChange(
            (int) $a['restaurant']->id,
            $a['pro_id'],
            'annual',
        );
        $quote['subscription_id'] = (int) $b['subscription']->id; // wrong restaurant's subscription

        $applied = app(SubscriptionService::class)->applyQuotedPlanChange($quote);
        $this->assertFalse($applied);

        $b['subscription']->refresh();
        $this->assertSame($b['basic_id'], (int) $b['subscription']->plan_id);

        Carbon::setTestNow();
    }

    public function test_zero_value_charge_is_rejected_on_initialize(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $suffix = uniqid();
        $planId = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'Zero Plan '.$suffix,
            'slug' => 'zero-plan-'.$suffix,
            'monthly_price' => 0,
            'annual_price' => 0,
            'max_categories' => 10,
            'max_menu_items' => 50,
            'max_qr_styles' => 3,
            'max_templates' => 3,
            'is_active' => 1,
            'display_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $restaurant = Restaurant::create([
            'name' => 'Zero Cafe '.$suffix,
            'slug' => 'zero-cafe-'.$suffix,
            'email' => 'zero-cafe-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        Manager::create([
            'username' => 'zero-mgr-'.$suffix,
            'email' => 'zero-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        $this->ensurePaystackConfigured();
        $result = app(PaymentGatewayService::class)->initializeSubscriptionPayment(
            (int) $restaurant->id,
            $planId,
            'monthly',
            'paystack',
        );
        $this->assertArrayHasKey('error', $result);
        $this->assertSame(0, DB::table('payments')->where('restaurant_id', $restaurant->id)->count());
    }

    public function test_duplicate_callback_is_idempotent(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedActiveAnnualBasicHalfway();
        $this->ensurePaystackConfigured();
        Http::fake([
            'api.paystack.co/*' => Http::response([
                'status' => true,
                'data' => ['authorization_url' => 'https://checkout.paystack.com/test', 'reference' => 'PS_dup'],
            ], 200),
        ]);

        $gateway = app(PaymentGatewayService::class);
        $gateway->initializeSubscriptionPayment((int) $ctx['restaurant']->id, $ctx['pro_id'], 'annual', 'paystack');
        $payment = DB::table('payments')->where('restaurant_id', $ctx['restaurant']->id)->orderByDesc('id')->first();
        $payload = [
            'reference' => $payment->transaction_reference,
            'amount' => (int) round(((float) $payment->amount) * 100),
            'currency' => 'NGN',
            'status' => 'success',
            'metadata' => ['plan_id' => $ctx['pro_id']],
        ];

        $this->assertTrue($gateway->processPlatformPaystackSuccess($payload));
        $this->assertTrue($gateway->processPlatformPaystackSuccess($payload));

        $ctx['subscription']->refresh();
        $this->assertSame($ctx['pro_id'], (int) $ctx['subscription']->plan_id);
        $this->assertTrue($ctx['subscription']->current_period_end->equalTo(Carbon::parse('2027-01-01 12:00:00')));
        Carbon::setTestNow();
    }

    public function test_concurrent_initialize_reuses_single_pending_payment(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedActiveAnnualBasicHalfway();
        $this->ensurePaystackConfigured();
        Http::fake([
            'api.paystack.co/*' => Http::response([
                'status' => true,
                'data' => ['authorization_url' => 'https://checkout.paystack.com/test', 'reference' => 'PS_c'],
            ], 200),
        ]);

        $gateway = app(PaymentGatewayService::class);
        $gateway->initializeSubscriptionPayment((int) $ctx['restaurant']->id, $ctx['pro_id'], 'annual', 'paystack');
        $gateway->initializeSubscriptionPayment((int) $ctx['restaurant']->id, $ctx['pro_id'], 'annual', 'paystack');

        $pendingCount = DB::table('payments')
            ->where('restaurant_id', $ctx['restaurant']->id)
            ->where('status', 'pending')
            ->where('plan_id', $ctx['pro_id'])
            ->count();
        $this->assertSame(1, $pendingCount);
        Carbon::setTestNow();
    }

    public function test_admin_manual_success_uses_intent_and_preserves_period(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $ctx = $this->seedActiveAnnualBasicHalfway();
        $periodStart = $ctx['subscription']->current_period_start->copy();
        $periodEnd = $ctx['subscription']->current_period_end->copy();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.payments.store'), [
                'action' => 'create_manual',
                'restaurant_id' => $ctx['restaurant']->id,
                'plan_id' => $ctx['pro_id'],
                'billing_cycle' => 'annual',
                'status' => 'success',
                'amount' => 1,
            ])
            ->assertRedirect();

        $ctx['subscription']->refresh();
        $this->assertSame($ctx['pro_id'], (int) $ctx['subscription']->plan_id);
        $this->assertTrue($ctx['subscription']->current_period_start->equalTo($periodStart));
        $this->assertTrue($ctx['subscription']->current_period_end->equalTo($periodEnd));

        $payment = DB::table('payments')->where('restaurant_id', $ctx['restaurant']->id)->orderByDesc('id')->first();
        $intent = app(SubscriptionPaymentLifecycleService::class)->extractBillingIntent($payment);
        $this->assertSame('preserve_period', $intent['apply_mode'] ?? null);
        Carbon::setTestNow();
    }

    public function test_admin_update_status_success_does_not_force_mismatched_apply(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $admin = Admin::query()->where('username', 'staging-admin')->first();
        if (! $admin) {
            $this->markTestSkipped('Staging admin not available.');
        }

        $ctx = $this->seedActiveAnnualBasicHalfway();
        $lifecycle = app(SubscriptionPaymentLifecycleService::class);
        $intent = $lifecycle->buildBillingIntent(
            (int) $ctx['restaurant']->id,
            (int) $ctx['subscription']->id,
            $ctx['pro_id'],
            'annual',
            60000,
            'residual_difference',
            'preserve_period',
        );

        $paymentId = (int) DB::table('payments')->insertGetId([
            'restaurant_id' => $ctx['restaurant']->id,
            'subscription_id' => $ctx['subscription']->id,
            'plan_id' => $ctx['pro_id'],
            'billing_cycle' => 'annual',
            'amount' => 60000,
            'currency' => 'NGN',
            'payment_gateway' => 'manual',
            'transaction_reference' => 'MANUAL-stale-'.uniqid(),
            'status' => 'pending',
            'gateway_response' => $lifecycle->encodeBillingIntentResponse(null, $intent),
            'created_at' => now(),
        ]);

        // Expire so live quote is full activate — must not apply residual payment as activate.
        Carbon::setTestNow(Carbon::parse('2027-01-02 12:00:00'));
        $ctx['subscription']->forceFill(['status' => 'expired'])->save();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.payments.store'), [
                'action' => 'update_status',
                'payment_id' => $paymentId,
                'new_status' => 'success',
                'note' => 'Admin confirming a stale residual payment after expiry',
            ])
            ->assertRedirect();

        $ctx['subscription']->refresh();
        $this->assertSame($ctx['basic_id'], (int) $ctx['subscription']->plan_id);
        $this->assertSame('success', DB::table('payments')->where('id', $paymentId)->value('status'));
        Carbon::setTestNow();
    }

    public function test_amount_mismatch_rejects_fulfillment_mutation(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedActiveAnnualBasicHalfway();
        $lifecycle = app(SubscriptionPaymentLifecycleService::class);
        $intent = $lifecycle->buildBillingIntent(
            (int) $ctx['restaurant']->id,
            (int) $ctx['subscription']->id,
            $ctx['pro_id'],
            'annual',
            60000,
            'residual_difference',
            'preserve_period',
        );

        $paymentId = (int) DB::table('payments')->insertGetId([
            'restaurant_id' => $ctx['restaurant']->id,
            'subscription_id' => $ctx['subscription']->id,
            'plan_id' => $ctx['pro_id'],
            'billing_cycle' => 'annual',
            'amount' => 1000, // tampered vs intent/live residual
            'currency' => 'NGN',
            'payment_gateway' => 'paystack',
            'transaction_reference' => 'PS_amt_'.uniqid(),
            'status' => 'success',
            'paid_at' => now(),
            'gateway_response' => $lifecycle->encodeBillingIntentResponse(null, $intent),
            'created_at' => now(),
        ]);

        $payment = DB::table('payments')->where('id', $paymentId)->first();
        app(PaymentGatewayService::class)->fulfillSubscriptionPayment($payment);

        $ctx['subscription']->refresh();
        $this->assertSame($ctx['basic_id'], (int) $ctx['subscription']->plan_id);
        Carbon::setTestNow();
    }
}
