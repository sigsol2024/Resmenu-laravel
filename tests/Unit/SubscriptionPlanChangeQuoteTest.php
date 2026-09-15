<?php

namespace Tests\Unit;

use App\Services\SubscriptionService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SubscriptionPlanChangeQuoteTest extends TestCase
{
    private function service(): SubscriptionService
    {
        return app(SubscriptionService::class);
    }

    private function dbReady(): bool
    {
        try {
            return Schema::hasTable('restaurants')
                && Schema::hasTable('subscriptions')
                && Schema::hasTable('subscription_plans')
                && Schema::hasTable('subscription_change_requests');
        } catch (\Throwable) {
            return false;
        }
    }

    /** @return array{restaurant_id:int, basic_id:int, pro_id:int} */
    private function seedPlansAndRestaurant(): array
    {
        $suffix = uniqid();
        $basicId = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'Quote Basic '.$suffix,
            'slug' => 'quote-basic-'.$suffix,
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
            'name' => 'Quote Pro '.$suffix,
            'slug' => 'quote-pro-'.$suffix,
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
        $restaurantId = (int) DB::table('restaurants')->insertGetId([
            'name' => 'Quote Rest '.$suffix,
            'slug' => 'quote-rest-'.$suffix,
            'email' => 'quote-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['restaurant_id' => $restaurantId, 'basic_id' => $basicId, 'pro_id' => $proId];
    }

    private function createSubscription(int $restaurantId, int $planId, array $attrs): int
    {
        return (int) DB::table('subscriptions')->insertGetId(array_merge([
            'restaurant_id' => $restaurantId,
            'plan_id' => $planId,
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ], $attrs));
    }

    public function test_active_annual_upgrade_halfway_pays_residual_and_preserves_dates(): void
    {
        if (! $this->dbReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        Carbon::setTestNow(Carbon::parse('2026-07-01 12:00:00'));
        $ctx = $this->seedPlansAndRestaurant();
        $periodStart = Carbon::parse('2026-01-01 12:00:00');
        $periodEnd = Carbon::parse('2027-01-01 12:00:00');
        $subId = $this->createSubscription($ctx['restaurant_id'], $ctx['basic_id'], [
            'billing_cycle' => 'annual',
            'status' => 'active',
            'current_period_start' => $periodStart,
            'current_period_end' => $periodEnd,
        ]);

        $quote = $this->service()->quotePlanChange($ctx['restaurant_id'], $ctx['pro_id'], 'annual');

        $this->assertSame('charge', $quote['outcome']);
        $this->assertSame('residual_difference', $quote['pricing_mode']);
        $this->assertSame('preserve_period', $quote['apply_mode']);
        $this->assertEqualsWithDelta(0.5, (float) $quote['remaining_fraction'], 0.01);
        $this->assertEqualsWithDelta(60000.0, (float) $quote['amount'], 1.0);

        $applied = $this->service()->applyQuotedPlanChange($quote);
        $this->assertTrue($applied);

        $sub = DB::table('subscriptions')->where('id', $subId)->first();
        $this->assertSame($ctx['pro_id'], (int) $sub->plan_id);
        $this->assertSame('annual', $sub->billing_cycle);
        $this->assertSame('active', $sub->status);
        $this->assertTrue(Carbon::parse($sub->current_period_start)->equalTo($periodStart));
        $this->assertTrue(Carbon::parse($sub->current_period_end)->equalTo($periodEnd));

        Carbon::setTestNow();
    }

    public function test_active_monthly_upgrade_uses_residual(): void
    {
        if (! $this->dbReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        Carbon::setTestNow(Carbon::parse('2026-06-16 12:00:00'));
        $ctx = $this->seedPlansAndRestaurant();
        $this->createSubscription($ctx['restaurant_id'], $ctx['basic_id'], [
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => Carbon::parse('2026-06-01 12:00:00'),
            'current_period_end' => Carbon::parse('2026-07-01 12:00:00'),
        ]);

        $quote = $this->service()->quotePlanChange($ctx['restaurant_id'], $ctx['pro_id'], 'monthly');

        $this->assertSame('charge', $quote['outcome']);
        $this->assertSame('residual_difference', $quote['pricing_mode']);
        $this->assertGreaterThan(0, (float) $quote['amount']);
        $this->assertLessThan(20000, (float) $quote['amount']);

        Carbon::setTestNow();
    }

    public function test_active_downgrade_is_scheduled_without_payment(): void
    {
        if (! $this->dbReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedPlansAndRestaurant();
        $periodEnd = now()->addDays(20);
        $subId = $this->createSubscription($ctx['restaurant_id'], $ctx['pro_id'], [
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => now()->subDays(10),
            'current_period_end' => $periodEnd,
        ]);

        $quote = $this->service()->quotePlanChange($ctx['restaurant_id'], $ctx['basic_id'], 'monthly');

        $this->assertSame('schedule_downgrade', $quote['outcome']);
        $this->assertSame(0.0, (float) $quote['amount']);

        $this->assertTrue($this->service()->applyScheduledDowngradeQuote($quote, 'admin'));
        $pending = DB::table('subscription_change_requests')
            ->where('subscription_id', $subId)
            ->where('status', 'pending')
            ->first();
        $this->assertNotNull($pending);
        $this->assertSame($ctx['basic_id'], (int) $pending->to_plan_id);

        $sub = DB::table('subscriptions')->where('id', $subId)->first();
        $this->assertSame($ctx['pro_id'], (int) $sub->plan_id);
    }

    public function test_trial_and_expired_and_no_subscription_are_full_price(): void
    {
        if (! $this->dbReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedPlansAndRestaurant();

        $none = $this->service()->quotePlanChange($ctx['restaurant_id'], $ctx['basic_id'], 'monthly');
        $this->assertSame('charge', $none['outcome']);
        $this->assertSame('full', $none['pricing_mode']);
        $this->assertEquals(10000.0, (float) $none['amount']);

        $this->createSubscription($ctx['restaurant_id'], $ctx['basic_id'], [
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(3),
            'current_period_start' => null,
            'current_period_end' => null,
        ]);
        $trial = $this->service()->quotePlanChange($ctx['restaurant_id'], $ctx['pro_id'], 'monthly');
        $this->assertSame('full', $trial['pricing_mode']);
        $this->assertEquals(20000.0, (float) $trial['amount']);

        DB::table('subscriptions')->where('restaurant_id', $ctx['restaurant_id'])->update([
            'status' => 'expired',
            'trial_ends_at' => null,
            'current_period_end' => now()->subDay(),
        ]);
        $expired = $this->service()->quotePlanChange($ctx['restaurant_id'], $ctx['pro_id'], 'annual');
        $this->assertSame('full', $expired['pricing_mode']);
        $this->assertEquals(240000.0, (float) $expired['amount']);
    }

    public function test_same_plan_rejected_and_annual_to_monthly_blocked(): void
    {
        if (! $this->dbReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $ctx = $this->seedPlansAndRestaurant();
        $this->createSubscription($ctx['restaurant_id'], $ctx['basic_id'], [
            'billing_cycle' => 'annual',
            'status' => 'active',
            'current_period_start' => now()->subMonths(2),
            'current_period_end' => now()->addMonths(10),
        ]);

        $same = $this->service()->quotePlanChange($ctx['restaurant_id'], $ctx['basic_id'], 'annual');
        $this->assertSame('already_on_plan', $same['outcome']);

        $blocked = $this->service()->quotePlanChange($ctx['restaurant_id'], $ctx['pro_id'], 'monthly');
        $this->assertSame('blocked', $blocked['outcome']);
        $this->assertSame('annual_to_monthly_blocked', $blocked['decision']['reason']);
    }

    public function test_monthly_to_annual_charges_full_and_activates_new_period(): void
    {
        if (! $this->dbReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        Carbon::setTestNow(Carbon::parse('2026-06-15 10:00:00'));
        $ctx = $this->seedPlansAndRestaurant();
        $oldEnd = Carbon::parse('2026-06-30 10:00:00');
        $subId = $this->createSubscription($ctx['restaurant_id'], $ctx['basic_id'], [
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_start' => Carbon::parse('2026-05-30 10:00:00'),
            'current_period_end' => $oldEnd,
        ]);

        $quote = $this->service()->quotePlanChange($ctx['restaurant_id'], $ctx['pro_id'], 'annual');
        $this->assertSame('charge', $quote['outcome']);
        $this->assertSame('full', $quote['pricing_mode']);
        $this->assertSame('activate', $quote['apply_mode']);
        $this->assertEquals(240000.0, (float) $quote['amount']);

        $this->assertTrue($this->service()->applyQuotedPlanChange($quote));
        $sub = DB::table('subscriptions')->where('id', $subId)->first();
        $this->assertSame($ctx['pro_id'], (int) $sub->plan_id);
        $this->assertSame('annual', $sub->billing_cycle);
        $this->assertTrue(Carbon::parse($sub->current_period_end)->greaterThan($oldEnd));
        $this->assertTrue(Carbon::parse($sub->current_period_start)->equalTo(now()));

        Carbon::setTestNow();
    }

    public function test_decision_blocks_annual_to_monthly_and_allows_monthly_to_annual(): void
    {
        $service = $this->service();
        $annual = [
            'plan_id' => 1,
            'billing_cycle' => 'annual',
            'status' => 'active',
            'current_period_end' => now()->addMonths(6)->toDateTimeString(),
            'display_order' => 1,
        ];
        $monthly = [
            'plan_id' => 1,
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_end' => now()->addDays(10)->toDateTimeString(),
            'display_order' => 1,
        ];
        $samePlan = ['id' => 1, 'display_order' => 1];
        $higher = ['id' => 2, 'display_order' => 2];

        $this->assertSame('blocked', $service->getSubscriptionChangeDecision($annual, $samePlan, 'monthly')['mode']);
        $this->assertSame('immediate', $service->getSubscriptionChangeDecision($monthly, $samePlan, 'annual')['mode']);
        $this->assertSame('cycle_upgrade', $service->getSubscriptionChangeDecision($monthly, $samePlan, 'annual')['type']);
        $this->assertSame('immediate', $service->getSubscriptionChangeDecision($monthly, $higher, 'annual')['mode']);
    }
}
