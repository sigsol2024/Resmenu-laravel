<?php

namespace Tests\Feature;

use App\Models\Manager;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PlanLimitVisibilityTest extends TestCase
{
    private function schemaReady(): bool
    {
        try {
            return Schema::hasTable('managers')
                && Schema::hasTable('restaurants')
                && Schema::hasTable('subscription_plans');
        } catch (\Throwable) {
            return false;
        }
    }

    public function test_checkout_page_includes_plan_limit_warning_panel(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $manager = Manager::query()->where('username', 'staging-manager')->first();
        if (! $manager) {
            $this->markTestSkipped('Run db:seed for staging manager credentials.');
        }

        $this->actingAs($manager, 'manager')
            ->get('/manager/billing/checkout')
            ->assertOk()
            ->assertSee('planCheckoutWarning', false)
            ->assertSee('Plan limits', false);
    }
}
