<?php

namespace Tests\Unit;

use App\Models\MenuItem;
use App\Services\MailService;
use App\Services\OrderSubmissionService;
use App\Services\PlanVisibilityService;
use App\Services\SubscriptionService;
use App\Support\PlanVisibilityResult;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class OrderSubmissionServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function dbAvailable(): bool
    {
        try {
            return Schema::hasTable('menu_items') && Schema::hasTable('restaurants');
        } catch (\Throwable) {
            return false;
        }
    }

    public function test_validate_cart_rejects_item_not_visible_on_public_menu(): void
    {
        if (! $this->dbAvailable()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $item = MenuItem::query()->where('is_available', 1)->first();
        if (! $item) {
            $this->markTestSkipped('No available menu item in database.');
        }

        $visibility = Mockery::mock(PlanVisibilityService::class);
        $visibility->shouldReceive('resolve')
            ->once()
            ->with((int) $item->restaurant_id)
            ->andReturn(new PlanVisibilityResult(
                categories: [],
                menuItems: [
                    (int) $item->id => [
                        'is_plan_hidden' => false,
                        'hidden_reason' => null,
                        'is_visible_on_public_menu' => false,
                    ],
                ],
                summary: [],
            ));

        $service = new OrderSubmissionService(
            app(SubscriptionService::class),
            Mockery::mock(MailService::class),
            $visibility,
        );

        $result = $service->validateCartPublic((int) $item->restaurant_id, [
            ['id' => (int) $item->id, 'quantity' => 1],
        ]);

        $this->assertFalse($result['success']);
        $this->assertContains('This menu item is no longer available.', $result['errors']);
    }

    public function test_validate_cart_rejects_unknown_item_in_visibility_map(): void
    {
        if (! $this->dbAvailable()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $item = MenuItem::query()->where('is_available', 1)->first();
        if (! $item) {
            $this->markTestSkipped('No available menu item in database.');
        }

        $visibility = Mockery::mock(PlanVisibilityService::class);
        $visibility->shouldReceive('resolve')
            ->once()
            ->with((int) $item->restaurant_id)
            ->andReturn(new PlanVisibilityResult(
                categories: [],
                menuItems: [],
                summary: [],
            ));

        $service = new OrderSubmissionService(
            app(SubscriptionService::class),
            Mockery::mock(MailService::class),
            $visibility,
        );

        $result = $service->validateCartPublic((int) $item->restaurant_id, [
            ['id' => (int) $item->id, 'quantity' => 1],
        ]);

        $this->assertFalse($result['success']);
        $this->assertContains('This menu item is no longer available.', $result['errors']);
    }
}
