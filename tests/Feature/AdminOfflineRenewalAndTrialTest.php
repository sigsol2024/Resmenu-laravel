<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Subscription;
use App\Services\PlanVisibilityService;
use App\Services\SubscriptionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

/**
 * Offline payments recorded by an admin update the subscription (including renewals of the
 * current plan); the Subscriptions page can only nudge trials by 7 days and never grants paid time.
 */
class AdminOfflineRenewalAndTrialTest extends TestCase
{
    private int $planId;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'app.key' => 'base64:'.base64_encode(random_bytes(32)),
            'database.default' => 'billing_sqlite',
            'database.connections.billing_sqlite' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => false],
            'cache.default' => 'array',
        ]);
        DB::purge('billing_sqlite');
        Cache::flush();
        $this->createSchema();

        $visibility = Mockery::mock(PlanVisibilityService::class);
        $visibility->shouldReceive('forgetCache');
        $this->app->instance(PlanVisibilityService::class, $visibility);

        Carbon::setTestNow(Carbon::parse('2026-10-02 12:00:00'));
        DB::table('admins')->insert(['id' => 1, 'username' => 'root', 'email' => 'root@example.com', 'password_hash' => 'x', 'is_super_admin' => 1, 'is_active' => 1]);
        DB::table('restaurants')->insert(['id' => 1, 'name' => 'Wazobia', 'slug' => 'wazobia']);
        $this->planId = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'Professional', 'slug' => 'professional', 'monthly_price' => 15000, 'annual_price' => 144000, 'is_active' => 1, 'display_order' => 2,
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        Mockery::close();
        parent::tearDown();
    }

    public function test_offline_payment_renews_the_current_plan_from_its_end_date_once(): void
    {
        $sub = $this->subscription(['status' => 'active', 'current_period_start' => '2026-09-15 12:00:00', 'current_period_end' => '2026-10-15 12:00:00']);

        $this->recordManual('success')->assertSessionHas('success', 'Manual payment recorded.');

        $sub->refresh();
        $this->assertSame('2026-11-15 12:00:00', $sub->current_period_end->format('Y-m-d H:i:s'));
        $this->assertSame('2026-09-15 12:00:00', $sub->current_period_start->format('Y-m-d H:i:s'));
        $this->assertSame('active', $sub->status);
        $payment = DB::table('payments')->first();
        $this->assertEquals(15000, (float) $payment->amount);
        $this->assertSame('renew', json_decode($payment->gateway_response, true)['billing_intent']['apply_mode']);

        // Re-marking the same payment successful must not add another month.
        $this->asAdmin()->post(route('admin.payments.store'), ['action' => 'update_status', 'payment_id' => $payment->id, 'new_status' => 'success', 'note' => 'Confirmed bank transfer again']);
        $this->assertSame('2026-11-15 12:00:00', $sub->fresh()->current_period_end->format('Y-m-d H:i:s'));
    }

    public function test_pending_renewal_applies_when_marked_successful(): void
    {
        $sub = $this->subscription(['status' => 'active', 'current_period_start' => '2026-09-15 12:00:00', 'current_period_end' => '2026-10-15 12:00:00']);

        $this->recordManual('pending');
        $this->assertSame('2026-10-15 12:00:00', $sub->fresh()->current_period_end->format('Y-m-d H:i:s'));

        $paymentId = (int) DB::table('payments')->value('id');
        $this->asAdmin()->post(route('admin.payments.store'), ['action' => 'update_status', 'payment_id' => $paymentId, 'new_status' => 'success', 'note' => 'Bank transfer received today']);

        $this->assertSame('2026-11-15 12:00:00', $sub->fresh()->current_period_end->format('Y-m-d H:i:s'));
    }

    public function test_offline_payment_converts_a_long_trial_into_a_paid_period(): void
    {
        $sub = $this->subscription(['status' => 'trial', 'trial_ends_at' => '2027-09-30 12:00:00']);

        $this->recordManual('success', 'annual');

        $sub->refresh();
        $this->assertSame('active', $sub->status);
        $this->assertNull($sub->trial_ends_at);
        $this->assertSame('2027-10-02 12:00:00', $sub->current_period_end->format('Y-m-d H:i:s'));
        $this->assertEquals(144000, (float) DB::table('payments')->value('amount'));
    }

    public function test_manager_checkout_still_treats_the_current_plan_as_already_owned(): void
    {
        $this->subscription(['status' => 'active', 'current_period_start' => '2026-09-15 12:00:00', 'current_period_end' => '2026-10-15 12:00:00']);
        $service = app(SubscriptionService::class);

        $this->assertSame('already_on_plan', $service->quotePlanChange(1, $this->planId, 'monthly')['outcome']);
        $this->assertSame('renew', $service->quotePlanChange(1, $this->planId, 'monthly', true)['apply_mode']);
    }

    public function test_trial_can_only_be_extended_by_seven_days(): void
    {
        $sub = $this->subscription(['status' => 'trial', 'trial_ends_at' => '2026-10-05 12:00:00']);

        $this->patchSubscription($sub, ['action' => 'extend_period', 'days' => 365])->assertSessionHasErrors('days');
        $this->assertSame('2026-10-05 12:00:00', $sub->fresh()->trial_ends_at->format('Y-m-d H:i:s'));

        $this->patchSubscription($sub, ['action' => 'extend_period', 'days' => 7])->assertSessionHas('success');
        $this->assertSame('2026-10-12 12:00:00', $sub->fresh()->trial_ends_at->format('Y-m-d H:i:s'));
    }

    public function test_reset_pulls_an_over_extended_trial_back_to_seven_days(): void
    {
        $sub = $this->subscription(['status' => 'trial', 'trial_ends_at' => '2027-09-30 12:00:00']);

        $this->patchSubscription($sub, ['action' => 'reset_trial'])->assertSessionHas('success');

        $this->assertSame('2026-10-09 12:00:00', $sub->fresh()->trial_ends_at->format('Y-m-d H:i:s'));
        $this->assertSame('subscription.trial_reset', DB::table('activity_logs')->value('action'));
    }

    public function test_paid_subscriptions_cannot_be_extended_without_a_payment(): void
    {
        $sub = $this->subscription(['status' => 'active', 'current_period_start' => '2026-09-15 12:00:00', 'current_period_end' => '2026-10-15 12:00:00']);

        $this->patchSubscription($sub, ['action' => 'extend_period', 'days' => 7])->assertSessionHas('error');
        $this->patchSubscription($sub, ['action' => 'reset_trial'])->assertSessionHas('error');

        $sub->refresh();
        $this->assertSame('2026-10-15 12:00:00', $sub->current_period_end->format('Y-m-d H:i:s'));
        $this->assertSame('active', $sub->status);
    }

    public function test_reset_never_lengthens_a_short_trial(): void
    {
        $sub = $this->subscription(['status' => 'trial', 'trial_ends_at' => '2026-10-04 12:00:00']);

        $this->patchSubscription($sub, ['action' => 'reset_trial'])->assertSessionHas('error');

        $this->assertSame('2026-10-04 12:00:00', $sub->fresh()->trial_ends_at->format('Y-m-d H:i:s'));
    }

    public function test_paid_subscription_set_back_to_trial_status_cannot_get_free_days(): void
    {
        $sub = $this->subscription(['status' => 'trial', 'current_period_start' => '2026-09-15 12:00:00', 'current_period_end' => '2026-10-15 12:00:00']);

        $this->patchSubscription($sub, ['action' => 'extend_period', 'days' => 7])->assertSessionHas('error');

        $this->assertSame('2026-10-15 12:00:00', $sub->fresh()->current_period_end->format('Y-m-d H:i:s'));
    }

    public function test_free_form_plan_or_cycle_edits_are_rejected(): void
    {
        $sub = $this->subscription(['status' => 'trial', 'trial_ends_at' => '2026-10-05 12:00:00']);
        $otherPlan = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'Enterprise', 'slug' => 'enterprise', 'monthly_price' => 50000, 'annual_price' => 480000, 'is_active' => 1, 'display_order' => 3,
        ]);

        $this->patchSubscription($sub, ['plan_id' => $otherPlan, 'billing_cycle' => 'annual', 'status' => 'active'])
            ->assertSessionHas('error', 'Invalid action.');

        $sub->refresh();
        $this->assertSame($this->planId, (int) $sub->plan_id);
        $this->assertSame('trial', $sub->status);
    }

    public function test_status_cannot_be_set_to_active_without_a_payment(): void
    {
        $sub = $this->subscription(['status' => 'expired', 'trial_ends_at' => '2026-09-25 12:00:00']);

        $this->patchSubscription($sub, ['action' => 'update_status', 'new_status' => 'active'])->assertSessionHas('error');

        $sub->refresh();
        $this->assertSame('expired', $sub->status);
        $this->assertNull($sub->current_period_end);
    }

    public function test_status_back_to_trial_only_before_any_paid_period(): void
    {
        $paid = $this->subscription(['status' => 'expired', 'current_period_start' => '2026-08-01 12:00:00', 'current_period_end' => '2026-09-01 12:00:00']);
        $this->patchSubscription($paid, ['action' => 'update_status', 'new_status' => 'trial'])->assertSessionHas('error');
        $this->assertSame('expired', $paid->fresh()->status);

        DB::table('restaurants')->insert(['id' => 2, 'name' => 'Second', 'slug' => 'second']);
        $lapsed = $this->subscription(['restaurant_id' => 2, 'status' => 'expired', 'trial_ends_at' => '2026-09-25 12:00:00']);
        $this->patchSubscription($lapsed, ['action' => 'update_status', 'new_status' => 'trial'])->assertSessionHas('success');

        $lapsed->refresh();
        $this->assertSame('trial', $lapsed->status);
        $this->assertSame('2026-10-09 12:00:00', $lapsed->trial_ends_at->format('Y-m-d H:i:s'));
    }

    public function test_subscriptions_page_offers_only_payment_safe_actions(): void
    {
        $this->subscription(['status' => 'trial', 'trial_ends_at' => '2027-09-30 12:00:00']);

        $this->asAdmin()->get(route('admin.subscriptions.index'))
            ->assertOk()
            ->assertSee('+ 7 days')
            ->assertSee('Shorten to end 7 days from today')
            ->assertSee('Set to Expired')
            ->assertDontSee('Set to Active')
            ->assertDontSee('+ 365 days');
    }

    private function subscription(array $attrs): Subscription
    {
        return Subscription::forceCreate(array_merge(['restaurant_id' => 1, 'plan_id' => $this->planId, 'billing_cycle' => 'monthly'], $attrs));
    }

    private function asAdmin(): static
    {
        return $this->actingAs(Admin::findOrFail(1), 'admin');
    }

    private function recordManual(string $status, string $cycle = 'monthly')
    {
        return $this->asAdmin()->post(route('admin.payments.store'), [
            'action' => 'create_manual', 'restaurant_id' => 1, 'plan_id' => $this->planId, 'billing_cycle' => $cycle, 'status' => $status,
        ]);
    }

    private function patchSubscription(Subscription $sub, array $data)
    {
        return $this->asAdmin()->patch(route('admin.subscriptions.update', $sub), $data);
    }

    private function createSchema(): void
    {
        Schema::create('admins', function (Blueprint $t) {
            $t->increments('id');
            $t->string('username');
            $t->string('email');
            $t->string('password_hash');
            $t->string('remember_token')->nullable();
            $t->boolean('is_super_admin')->default(false);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('restaurants', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->string('slug');
            $t->timestamps();
        });
        Schema::create('subscription_plans', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name');
            $t->string('slug');
            $t->text('description')->nullable();
            $t->decimal('monthly_price', 10, 2)->default(0);
            $t->decimal('annual_price', 10, 2)->default(0);
            $t->integer('max_categories')->default(5);
            $t->integer('max_menu_items')->default(50);
            $t->integer('max_qr_styles')->default(3);
            $t->integer('max_templates')->default(3);
            $t->text('features')->nullable();
            $t->boolean('is_active')->default(true);
            $t->integer('display_order')->default(0);
            $t->timestamps();
        });
        Schema::create('subscriptions', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('restaurant_id');
            $t->integer('plan_id');
            $t->string('billing_cycle')->default('monthly');
            $t->string('status')->default('trial');
            $t->dateTime('trial_ends_at')->nullable();
            $t->dateTime('current_period_start')->nullable();
            $t->dateTime('current_period_end')->nullable();
            $t->dateTime('cancelled_at')->nullable();
            $t->timestamps();
        });
        Schema::create('payments', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('restaurant_id');
            $t->integer('subscription_id');
            $t->integer('plan_id')->nullable();
            $t->string('billing_cycle')->nullable();
            $t->decimal('amount', 10, 2);
            $t->string('currency', 3)->default('NGN');
            $t->string('payment_gateway');
            $t->string('transaction_reference')->nullable();
            $t->text('gateway_response')->nullable();
            $t->string('status')->default('pending');
            $t->dateTime('paid_at')->nullable();
            $t->timestamp('created_at')->nullable();
        });
        Schema::create('subscription_change_requests', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('restaurant_id');
            $t->integer('subscription_id');
            $t->integer('from_plan_id');
            $t->integer('to_plan_id');
            $t->string('from_billing_cycle');
            $t->string('to_billing_cycle');
            $t->string('change_type');
            $t->dateTime('effective_at');
            $t->string('status')->default('pending');
            $t->string('requested_by')->nullable();
            $t->dateTime('applied_at')->nullable();
            $t->timestamps();
        });
        Schema::create('activity_logs', function (Blueprint $t) {
            $t->increments('id');
            $t->string('actor_type');
            $t->integer('actor_id')->nullable();
            $t->integer('restaurant_id')->nullable();
            $t->string('action');
            $t->string('subject_type')->nullable();
            $t->integer('subject_id')->nullable();
            $t->text('old_values')->nullable();
            $t->text('new_values')->nullable();
            $t->string('ip')->nullable();
            $t->string('user_agent', 512)->nullable();
            $t->timestamp('created_at')->nullable();
        });
    }
}
