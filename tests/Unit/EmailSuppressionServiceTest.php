<?php

namespace Tests\Unit;

use App\Services\EmailSuppressionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmailSuppressionServiceTest extends TestCase
{
    public function test_is_suppressed_returns_false_for_unknown_email(): void
    {
        if (! $this->tableAvailable()) {
            $this->markTestSkipped('email_delivery_suppressions table not available.');
        }

        $service = app(EmailSuppressionService::class);
        $this->assertFalse($service->isSuppressed('not-listed@example.com'));
    }

    public function test_add_suppression_blocks_subsequent_lookup(): void
    {
        if (! $this->tableAvailable()) {
            $this->markTestSkipped('email_delivery_suppressions table not available.');
        }

        $email = 'blocked-'.uniqid().'@example.com';
        $service = app(EmailSuppressionService::class);

        $this->assertTrue($service->addSuppression($email, 'hard_bounce', 'test'));
        $this->assertTrue($service->isSuppressed($email));

        DB::table('email_delivery_suppressions')
            ->where('email_sha256', hash('sha256', strtolower(trim($email))))
            ->delete();
    }

    public function test_registration_otp_send_returns_false_for_suppressed_email(): void
    {
        if (! $this->tableAvailable()) {
            $this->markTestSkipped('email_delivery_suppressions table not available.');
        }

        $email = 'otp-blocked-'.uniqid().'@example.com';
        app(EmailSuppressionService::class)->addSuppression($email);

        $sent = app(\App\Services\RegistrationOtpService::class)->send($email, '127.0.0.1');
        $this->assertFalse($sent);

        DB::table('email_delivery_suppressions')
            ->where('email_sha256', hash('sha256', strtolower(trim($email))))
            ->delete();
    }

    private function tableAvailable(): bool
    {
        try {
            return Schema::hasTable('email_delivery_suppressions');
        } catch (\Throwable) {
            return false;
        }
    }
}
