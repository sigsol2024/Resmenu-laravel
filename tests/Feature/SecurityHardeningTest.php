<?php

namespace Tests\Feature;

use App\Models\Manager;
use App\Models\Order;
use App\Models\Restaurant;
use App\Services\PendingOnlinePaymentService;
use App\Services\RecaptchaService;
use App\Support\OrderConfirmationToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['resmenu.app_hmac_secret' => 'feature-test-hmac-secret-key!']);
    }

    private function legacySchemaAvailable(): bool
    {
        try {
            return Schema::hasTable('orders')
                && Schema::hasTable('restaurants')
                && Schema::hasTable('managers');
        } catch (\Throwable) {
            return false;
        }
    }

    public function test_health_endpoint_exposes_minimal_fields_only(): void
    {
        $response = $this->get('/health');

        $response->assertOk();
        $response->assertJsonStructure(['status', 'db']);
        $response->assertJsonMissing(['env', 'upload_root', 'templates']);
    }

    public function test_order_confirmation_without_token_returns_not_found(): void
    {
        if (! $this->legacySchemaAvailable()) {
            $this->markTestSkipped('Legacy schema not available.');
        }

        $restaurant = Restaurant::create([
            'name' => 'Test Cafe',
            'slug' => 'test-cafe-'.uniqid(),
            'email' => 'cafe@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        $order = Order::create([
            'restaurant_id' => $restaurant->id,
            'order_number' => 'ABC12345',
            'customer_name' => 'Jane',
            'customer_phone' => '08000000000',
            'customer_email' => 'jane@test.com',
            'delivery_address' => '123 Test Street',
            'status' => 'pending',
            'subtotal' => 10,
            'delivery_fee' => 0,
            'tax' => 0,
            'total' => 10,
        ]);

        $this->get('/orders/'.$order->id.'/confirmation')->assertNotFound();
    }

    public function test_order_confirmation_with_valid_token_succeeds(): void
    {
        if (! $this->legacySchemaAvailable()) {
            $this->markTestSkipped('Legacy schema not available.');
        }

        $restaurant = Restaurant::create([
            'name' => 'Test Cafe',
            'slug' => 'test-cafe-'.uniqid(),
            'email' => 'cafe@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        $order = Order::create([
            'restaurant_id' => $restaurant->id,
            'order_number' => 'ABC12345',
            'customer_name' => 'Jane',
            'customer_phone' => '08000000000',
            'customer_email' => 'jane@test.com',
            'delivery_address' => '123 Test Street',
            'status' => 'pending',
            'subtotal' => 10,
            'delivery_fee' => 0,
            'tax' => 0,
            'total' => 10,
        ]);

        $params = OrderConfirmationToken::queryParams($order->id, $restaurant->slug);
        $this->get('/orders/'.$order->id.'/confirmation?'.http_build_query($params))->assertOk();
    }

    public function test_email_suppression_webhook_rejects_missing_secret(): void
    {
        config(['resmenu.reg_otp_bounce_webhook_secret' => '']);

        $this->postJson('/api/webhooks/email-suppression')->assertUnauthorized();
    }

    public function test_email_suppression_webhook_accepts_valid_secret(): void
    {
        config(['resmenu.reg_otp_bounce_webhook_secret' => 'test-secret']);

        $this->postJson('/api/webhooks/email-suppression', [], [
            'X-Webhook-Secret' => 'test-secret',
        ])->assertOk();
    }

    public function test_payment_callback_rejects_unverified_reference(): void
    {
        try {
            if (! Schema::hasTable('pending_online_payments')) {
                $this->markTestSkipped('pending_online_payments table not available.');
            }
        } catch (\Throwable) {
            $this->markTestSkipped('Database not available.');
        }

        Http::fake([
            'api.paystack.co/*' => Http::response(['data' => ['status' => 'failed']], 200),
        ]);

        $restaurant = Restaurant::create([
            'name' => 'Paystack Test',
            'slug' => 'paystack-test-'.uniqid(),
            'email' => 'paystack@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        DB::table('pending_online_payments')->insert([
            'reference' => 'POP_test_ref',
            'restaurant_id' => $restaurant->id,
            'payment_type' => 'order',
            'gateway' => 'paystack',
            'cart_json' => '[]',
            'customer_name' => 'A',
            'customer_phone' => '1',
            'customer_email' => 'a@test.com',
            'delivery_address' => '',
            'subtotal' => 10,
            'delivery_fee' => 0,
            'tax' => 0,
            'total' => 10,
            'created_at' => now(),
        ]);

        $this->get('/order-payment/callback/paystack?reference=POP_test_ref&slug=demo')
            ->assertRedirect();

        $this->assertDatabaseHas('pending_online_payments', ['reference' => 'POP_test_ref']);
    }

    public function test_manager_tenant_uses_manager_restaurant_not_session_override(): void
    {
        if (! $this->legacySchemaAvailable()) {
            $this->markTestSkipped('Legacy schema not available.');
        }

        $restaurantA = Restaurant::create([
            'name' => 'A',
            'slug' => 'rest-a-'.uniqid(),
            'email' => 'a@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        $restaurantB = Restaurant::create([
            'name' => 'B',
            'slug' => 'rest-b-'.uniqid(),
            'email' => 'b@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        $manager = Manager::create([
            'username' => 'mgr'.uniqid(),
            'email' => 'mgr'.uniqid().'@test.com',
            'password_hash' => bcrypt('password'),
            'restaurant_id' => $restaurantA->id,
        ]);

        $this->actingAs($manager, 'manager')
            ->withSession(['restaurant_id' => $restaurantB->id])
            ->get('/manager/dashboard')
            ->assertOk();

        $this->assertEquals($restaurantA->id, session('restaurant_id'));
    }

    public function test_api_rejects_direct_card_payment_method(): void
    {
        if (! $this->legacySchemaAvailable()) {
            $this->markTestSkipped('Legacy schema not available.');
        }

        $restaurant = Restaurant::create([
            'name' => 'Test Cafe',
            'slug' => 'test-cafe-'.uniqid(),
            'email' => 'cafe@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        $this->postJson('/api/orders', [
            'slug' => $restaurant->slug,
            'cart_json' => '[]',
            'payment_method' => 'card',
            'customer_name' => 'Jane',
            'customer_phone' => '080',
            'customer_email' => 'jane@test.com',
        ])->assertStatus(400);
    }

    public function test_pending_payment_fulfillment_is_idempotent_for_missing_reference(): void
    {
        try {
            DB::connection()->getPdo();
        } catch (\Throwable) {
            $this->markTestSkipped('Database not available.');
        }

        Cache::flush();

        $service = app(PendingOnlinePaymentService::class);
        $reference = 'POP_missing_'.uniqid();
        $first = $service->fulfillFromWebhook($reference, 'paystack');
        $second = $service->fulfillFromWebhook($reference, 'paystack');

        $this->assertTrue($first['already_processed'] ?? false);
        $this->assertTrue($second['already_processed'] ?? false);
    }

    public function test_recaptcha_skips_verification_in_testing_environment(): void
    {
        $this->app->detectEnvironment(fn () => 'testing');
        config([
            'resmenu.recaptcha_site_key' => '',
            'resmenu.recaptcha_secret_key' => '',
        ]);

        $service = app(RecaptchaService::class);
        $this->assertFalse($service->shouldEnforce());
        $this->assertTrue($service->verifyToken(''));
    }
}
