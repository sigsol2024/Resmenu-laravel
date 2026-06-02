<?php

namespace Tests\Unit;

use App\Services\SubscriptionPaymentLifecycleService;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class SubscriptionPaymentLifecycleServiceTest extends TestCase
{
    private function service(): SubscriptionPaymentLifecycleService
    {
        return app(SubscriptionPaymentLifecycleService::class);
    }

    public function test_stale_pending_displays_as_failed(): void
    {
        Config::set('resmenu.subscription_payment_pending_hours', 6);

        $display = $this->service()->displayStatusForPayment([
            'status' => 'pending',
            'created_at' => now()->subHours(7)->toDateTimeString(),
        ]);

        $this->assertSame('failed', $display['status']);
        $this->assertSame('Failed', $display['label']);
        $this->assertTrue($display['is_stale']);
    }

    public function test_recent_pending_displays_as_pending(): void
    {
        Config::set('resmenu.subscription_payment_pending_hours', 6);

        $display = $this->service()->displayStatusForPayment([
            'status' => 'pending',
            'created_at' => now()->subHour()->toDateTimeString(),
        ]);

        $this->assertSame('pending', $display['status']);
        $this->assertSame('Pending', $display['label']);
        $this->assertFalse($display['is_stale']);
    }

    public function test_success_displays_correctly(): void
    {
        $display = $this->service()->displayStatusForPayment([
            'status' => 'success',
            'created_at' => now()->toDateTimeString(),
        ]);

        $this->assertSame('Success', $display['label']);
        $this->assertSame('success', $display['css_class']);
    }

    public function test_pending_threshold_hours_defaults_to_six(): void
    {
        Config::set('resmenu.subscription_payment_pending_hours', 6);

        $this->assertSame(6, $this->service()->pendingThresholdHours());
    }
}
