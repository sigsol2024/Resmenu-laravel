<?php

namespace Tests\Unit;

use App\Support\OrderConfirmationToken;
use Tests\TestCase;

class OrderConfirmationTokenTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['resmenu.app_hmac_secret' => 'unit-test-hmac-secret-key-32b!']);
    }

    public function test_build_and_verify_round_trip(): void
    {
        $params = OrderConfirmationToken::build(42, 'demo-restaurant');
        $this->assertNotNull($params);
        $this->assertTrue(OrderConfirmationToken::verify($params, 42));
    }

    public function test_verify_rejects_wrong_order_id(): void
    {
        $params = OrderConfirmationToken::build(42, 'demo-restaurant');
        $this->assertFalse(OrderConfirmationToken::verify($params, 99));
    }

    public function test_verify_rejects_expired_token(): void
    {
        $params = OrderConfirmationToken::build(42, 'demo-restaurant', -10);
        $this->assertFalse(OrderConfirmationToken::verify($params, 42));
    }

    public function test_confirmation_url_includes_signed_query(): void
    {
        $url = OrderConfirmationToken::confirmationUrl(5, 'cafe');
        $this->assertStringContainsString('/orders/5/confirmation', $url);
        $this->assertStringContainsString('sig=', $url);
        $this->assertStringContainsString('exp=', $url);
    }
}
