<?php

namespace Tests\Unit;

use App\Services\PaymentGatewayService;
use Tests\TestCase;

class PaymentGatewayServiceTest extends TestCase
{
    private function service(): PaymentGatewayService
    {
        return app(PaymentGatewayService::class);
    }

    /** @return object{transaction_reference:string,amount:float,plan_id:int,subscription_id:int} */
    private function paystackPayment(float $amount = 5000.0, int $planId = 2): object
    {
        return (object) [
            'transaction_reference' => 'PS_test_ref',
            'amount' => $amount,
            'plan_id' => $planId,
            'subscription_id' => 1,
        ];
    }

    public function test_verify_platform_payment_accepts_matching_paystack_payload(): void
    {
        $payment = $this->paystackPayment();
        $data = [
            'reference' => 'PS_test_ref',
            'currency' => 'NGN',
            'amount' => 500000,
            'metadata' => ['plan_id' => 2],
        ];

        $this->assertTrue($this->service()->verifyPlatformPayment($data, $payment, 'paystack'));
    }

    public function test_verify_platform_payment_rejects_paystack_amount_mismatch(): void
    {
        $payment = $this->paystackPayment();
        $data = [
            'reference' => 'PS_test_ref',
            'currency' => 'NGN',
            'amount' => 499900,
            'metadata' => ['plan_id' => 2],
        ];

        $this->assertFalse($this->service()->verifyPlatformPayment($data, $payment, 'paystack'));
    }

    public function test_verify_platform_payment_rejects_paystack_reference_mismatch(): void
    {
        $payment = $this->paystackPayment();
        $data = [
            'reference' => 'PS_other_ref',
            'currency' => 'NGN',
            'amount' => 500000,
            'metadata' => ['plan_id' => 2],
        ];

        $this->assertFalse($this->service()->verifyPlatformPayment($data, $payment, 'paystack'));
    }

    public function test_verify_platform_payment_rejects_paystack_currency_mismatch(): void
    {
        $payment = $this->paystackPayment();
        $data = [
            'reference' => 'PS_test_ref',
            'currency' => 'USD',
            'amount' => 500000,
            'metadata' => ['plan_id' => 2],
        ];

        $this->assertFalse($this->service()->verifyPlatformPayment($data, $payment, 'paystack'));
    }

    public function test_verify_platform_payment_rejects_plan_id_tamper(): void
    {
        $payment = $this->paystackPayment(5000.0, 2);
        $data = [
            'reference' => 'PS_test_ref',
            'currency' => 'NGN',
            'amount' => 500000,
            'metadata' => ['plan_id' => 99],
        ];

        $this->assertFalse($this->service()->verifyPlatformPayment($data, $payment, 'paystack'));
    }

    public function test_verify_platform_payment_accepts_matching_flutterwave_payload(): void
    {
        $payment = (object) [
            'transaction_reference' => 'FLW_test_ref',
            'amount' => 2500.50,
            'plan_id' => 3,
            'subscription_id' => 1,
        ];
        $data = [
            'tx_ref' => 'FLW_test_ref',
            'currency' => 'NGN',
            'amount' => 2500.50,
            'meta' => ['plan_id' => 3],
        ];

        $this->assertTrue($this->service()->verifyPlatformPayment($data, $payment, 'flutterwave'));
    }

    public function test_verify_platform_payment_rejects_flutterwave_amount_mismatch(): void
    {
        $payment = (object) [
            'transaction_reference' => 'FLW_test_ref',
            'amount' => 2500.50,
            'plan_id' => 3,
            'subscription_id' => 1,
        ];
        $data = [
            'tx_ref' => 'FLW_test_ref',
            'currency' => 'NGN',
            'amount' => 2400.00,
            'meta' => ['plan_id' => 3],
        ];

        $this->assertFalse($this->service()->verifyPlatformPayment($data, $payment, 'flutterwave'));
    }

    public function test_verify_platform_payment_rejects_unknown_gateway(): void
    {
        $payment = $this->paystackPayment();

        $this->assertFalse($this->service()->verifyPlatformPayment([], $payment, 'stripe'));
    }
}
