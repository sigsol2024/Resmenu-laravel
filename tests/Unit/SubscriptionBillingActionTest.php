<?php

namespace Tests\Unit;

use App\Services\SubscriptionService;
use Tests\TestCase;

class SubscriptionBillingActionTest extends TestCase
{
    private function service(): SubscriptionService
    {
        return app(SubscriptionService::class);
    }

    public function test_expired_same_plan_offers_renew_not_current_plan(): void
    {
        $subscription = [
            'plan_id' => 2,
            'plan_slug' => 'professional',
            'billing_cycle' => 'monthly',
            'status' => 'expired',
            'display_order' => 2,
            'max_categories' => 50,
            'max_menu_items' => 400,
        ];
        $targetPlan = ['id' => 2, 'display_order' => 2];

        $decision = $this->service()->getSubscriptionChangeDecision($subscription, $targetPlan, 'monthly');

        $this->assertSame('immediate', $decision['mode']);
        $this->assertSame('renew', $decision['type']);

        $primary = $this->service()->getPrimaryBillingAction($subscription);

        $this->assertSame('renew', $primary['type']);
        $this->assertSame('Renew Plan', $primary['label']);
        $this->assertSame('btn-renew', $primary['css_class']);

        $button = $this->service()->getPlanChangeButtonPresentation($decision);
        $this->assertSame('Renew', $button['label']);
        $this->assertStringContainsString('btn-renew', $button['button_class']);
    }

    public function test_active_same_plan_shows_current_not_renew(): void
    {
        $subscription = [
            'plan_id' => 2,
            'plan_slug' => 'professional',
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'current_period_end' => now()->addMonth()->toDateTimeString(),
            'display_order' => 2,
        ];
        $targetPlan = ['id' => 2, 'display_order' => 2];

        $decision = $this->service()->getSubscriptionChangeDecision($subscription, $targetPlan, 'monthly');

        $this->assertSame('none', $decision['mode']);

        $primary = $this->service()->getPrimaryBillingAction($subscription);

        $this->assertSame('upgrade', $primary['type']);
        $this->assertSame('Upgrade Plan', $primary['label']);
    }

    public function test_billing_period_label_shows_expired_on_when_inactive(): void
    {
        $subscription = [
            'status' => 'expired',
            'current_period_end' => '2026-07-01 00:00:00',
        ];
        $statusInfo = $this->service()->getSubscriptionStatusInfo($subscription);

        $period = $this->service()->getBillingPeriodLabel($subscription, $statusInfo);

        $this->assertSame('Expired On', $period['label']);
        $this->assertSame('Jul 1, 2026', $period['value']);
    }

    public function test_missing_subscription_is_treated_as_new_immediate_subscribe(): void
    {
        $decision = $this->service()->getSubscriptionChangeDecision(null, ['id' => 2, 'display_order' => 2], 'monthly');

        $this->assertSame('immediate', $decision['mode']);
        $this->assertSame('new', $decision['type']);
        $this->assertSame('new_subscription', $decision['reason']);
    }
}
