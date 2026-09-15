<?php

namespace Tests\Feature;

use App\Models\Manager;
use App\Models\Restaurant;
use App\Services\PaymentGatewayService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ManagerGatewayPlanChangePaymentTest extends TestCase
{
    private function schemaReady(): bool
    {
        try {
            return Schema::hasTable('restaurants')
                && Schema::hasTable('managers')
                && Schema::hasTable('subscriptions')
                && Schema::hasTable('subscription_plans')
                && Schema::hasTable('payments')
                && Schema::hasTable('payment_settings');
        } catch (\Throwable) {
            return false;
        }
    }

    public function test_gateway_initialize_uses_residual_amount_for_active_upgrade(): void
    {
        if (! $this->schemaReady()) {
            $this->markTestSkipped('Database schema not available.');
        }

        $suffix = uniqid();
        $basicId = (int) DB::table('subscription_plans')->insertGetId([
            'name' => 'Gw Basic '.$suffix,
            'slug' => 'gw-basic-'.$suffix,
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
            'name' => 'Gw Pro '.$suffix,
            'slug' => 'gw-pro-'.$suffix,
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
            'name' => 'Gw Cafe '.$suffix,
            'slug' => 'gw-cafe-'.$suffix,
            'email' => 'gw-cafe-'.$suffix.'@example.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        Manager::create([
            'username' => 'gw-mgr-'.$suffix,
            'email' => 'gw-mgr-'.$suffix.'@example.com',
            'password_hash' => Hash::make('password12'),
            'restaurant_id' => $restaurant->id,
        ]);

        Carbon::setTestNow(Carbon::parse('2026-07-01 12:00:00'));
        DB::table('subscriptions')->insert([
            'restaurant_id' => $restaurant->id,
            'plan_id' => $basicId,
            'billing_cycle' => 'annual',
            'status' => 'active',
            'current_period_start' => Carbon::parse('2026-01-01 12:00:00'),
            'current_period_end' => Carbon::parse('2027-01-01 12:00:00'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        Http::fake([
            'api.paystack.co/*' => Http::response([
                'status' => true,
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/test',
                    'reference' => 'PS_test_ref',
                ],
            ], 200),
        ]);

        $result = app(PaymentGatewayService::class)->initializeSubscriptionPayment(
            (int) $restaurant->id,
            $proId,
            'annual',
            'paystack',
        );

        $this->assertArrayHasKey('redirect_url', $result);
        $payment = DB::table('payments')->where('restaurant_id', $restaurant->id)->orderByDesc('id')->first();
        $this->assertNotNull($payment);
        $this->assertEqualsWithDelta(60000.0, (float) $payment->amount, 1.0);
        $this->assertSame($proId, (int) $payment->plan_id);

        $blocked = app(PaymentGatewayService::class)->initializeSubscriptionPayment(
            (int) $restaurant->id,
            $proId,
            'monthly',
            'paystack',
        );
        $this->assertArrayHasKey('error', $blocked);

        Carbon::setTestNow();
    }
}
