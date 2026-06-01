<?php

namespace Tests\Feature;

use App\Models\Manager;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Services\BankTransferService;
use App\Services\PendingOnlinePaymentService;
use App\Services\RecaptchaService;
use App\Support\OrderConfirmationToken;
use App\Support\ReservationConfirmationToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
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

        $order = Order::forceCreate([
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

        $order = Order::forceCreate([
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

    public function test_reservation_confirmation_without_token_returns_not_found(): void
    {
        try {
            if (! Schema::hasTable('table_reservations')) {
                $this->markTestSkipped('table_reservations not available.');
            }
        } catch (\Throwable) {
            $this->markTestSkipped('Database not available.');
        }

        $restaurant = Restaurant::create([
            'name' => 'Res Cafe',
            'slug' => 'res-cafe-'.uniqid(),
            'email' => 'res@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        $reservationId = DB::table('table_reservations')->insertGetId([
            'restaurant_id' => $restaurant->id,
            'reservation_number' => 'R12345678',
            'guest_name' => 'Guest',
            'guest_email' => 'guest@test.com',
            'guest_phone' => '080',
            'reservation_date' => now()->toDateString(),
            'reservation_time' => '18:00:00',
            'party_size' => 2,
            'status' => 'pending',
            'deposit_amount' => 0,
            'deposit_paid' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/reservations/'.$reservationId.'/confirmation')->assertNotFound();
    }

    public function test_public_restaurants_api_omits_email(): void
    {
        if (! $this->legacySchemaAvailable()) {
            $this->markTestSkipped('Legacy schema not available.');
        }

        Restaurant::create([
            'name' => 'Public List',
            'slug' => 'public-list-'.uniqid(),
            'email' => 'secret@restaurant.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        $response = $this->getJson('/api/restaurants');
        $response->assertOk();
        $payload = json_encode($response->json());
        $this->assertStringNotContainsString('secret@restaurant.com', $payload);
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

    public function test_bank_transfer_customer_claim_records_customer_claimed_status(): void
    {
        try {
            if (! Schema::hasTable('pending_bank_transfers')) {
                $this->markTestSkipped('pending_bank_transfers table not available.');
            }
        } catch (\Throwable) {
            $this->markTestSkipped('Database not available.');
        }

        $restaurant = Restaurant::create([
            'name' => 'Bank Cafe',
            'slug' => 'bank-cafe-'.uniqid(),
            'email' => 'bank@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        $token = 'tok_'.bin2hex(random_bytes(8));
        $draftId = DB::table('pending_bank_transfers')->insertGetId([
            'token' => $token,
            'restaurant_id' => $restaurant->id,
            'payment_type' => 'order',
            'cart_json' => '[]',
            'customer_name' => 'Pat',
            'customer_phone' => '080',
            'customer_email' => 'pat@test.com',
            'delivery_address' => '',
            'subtotal' => 10,
            'delivery_fee' => 0,
            'tax' => 0,
            'total' => 10,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $result = app(BankTransferService::class)->customerClaimPayment($token);

        $this->assertTrue($result['success'] ?? false);
        $this->assertDatabaseHas('pending_bank_transfers', [
            'id' => $draftId,
            'status' => 'customer_claimed',
        ]);
    }

    public function test_expired_subscription_redirects_manager_write_actions_to_billing(): void
    {
        if (! $this->legacySchemaAvailable()) {
            $this->markTestSkipped('Legacy schema not available.');
        }

        $restaurant = Restaurant::create([
            'name' => 'Locked Cafe',
            'slug' => 'locked-cafe-'.uniqid(),
            'email' => 'locked@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        $planId = DB::table('subscription_plans')->where('is_active', 1)->value('id');
        if (! $planId) {
            $this->markTestSkipped('No subscription plans in database.');
        }

        Subscription::forceCreate([
            'restaurant_id' => $restaurant->id,
            'plan_id' => (int) $planId,
            'billing_cycle' => 'monthly',
            'status' => 'expired',
            'trial_ends_at' => now()->subDay(),
        ]);

        $sectionId = DB::table('sections')->insertGetId([
            'restaurant_id' => $restaurant->id,
            'name' => 'Main',
            'slug' => 'main-'.uniqid(),
            'display_order' => 0,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $manager = Manager::create([
            'username' => 'locked'.uniqid(),
            'email' => 'locked'.uniqid().'@test.com',
            'password_hash' => bcrypt('password'),
            'restaurant_id' => $restaurant->id,
        ]);

        $this->actingAs($manager, 'manager')
            ->withSession(['restaurant_id' => $restaurant->id])
            ->post(route('manager.categories.store'), [
                'name' => 'Blocked Category',
                'section_id' => $sectionId,
            ])
            ->assertRedirect(route('manager.billing.index', ['upgrade_required' => 1]));
    }

    public function test_manager_cannot_assign_category_to_another_restaurants_section(): void
    {
        if (! $this->legacySchemaAvailable()) {
            $this->markTestSkipped('Legacy schema not available.');
        }

        $planId = DB::table('subscription_plans')->where('is_active', 1)->value('id');
        if (! $planId) {
            $this->markTestSkipped('No subscription plans in database.');
        }

        $restaurantA = Restaurant::create([
            'name' => 'Tenant A',
            'slug' => 'tenant-a-'.uniqid(),
            'email' => 'a@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);
        $restaurantB = Restaurant::create([
            'name' => 'Tenant B',
            'slug' => 'tenant-b-'.uniqid(),
            'email' => 'b@test.com',
            'is_active' => 1,
            'template_id' => 4,
        ]);

        foreach ([$restaurantA, $restaurantB] as $restaurant) {
            Subscription::forceCreate([
                'restaurant_id' => $restaurant->id,
                'plan_id' => (int) $planId,
                'billing_cycle' => 'monthly',
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(7),
            ]);
        }

        $sectionBId = DB::table('sections')->insertGetId([
            'restaurant_id' => $restaurantB->id,
            'name' => 'B Section',
            'slug' => 'b-section-'.uniqid(),
            'display_order' => 0,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managerA = Manager::create([
            'username' => 'tenanta'.uniqid(),
            'email' => 'tenanta'.uniqid().'@test.com',
            'password_hash' => bcrypt('password'),
            'restaurant_id' => $restaurantA->id,
        ]);

        $this->actingAs($managerA, 'manager')
            ->withSession(['restaurant_id' => $restaurantA->id])
            ->post(route('manager.categories.store'), [
                'name' => 'Cross Tenant',
                'section_id' => $sectionBId,
            ])
            ->assertSessionHasErrors('section_id');
    }

    public function test_login_rate_limits_repeated_failures_per_username(): void
    {
        try {
            DB::connection()->getPdo();
        } catch (\Throwable) {
            $this->markTestSkipped('Database not available.');
        }

        RateLimiter::clear('login-user:ratelimit-user');

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.submit'), [
                'username' => 'ratelimit-user',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post(route('login.submit'), [
            'username' => 'ratelimit-user',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertStringContainsStringIgnoringCase('too many', session('errors')->get('username')[0] ?? '');
    }
}
