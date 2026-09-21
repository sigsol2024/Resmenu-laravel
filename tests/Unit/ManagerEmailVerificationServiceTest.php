<?php

namespace Tests\Unit;

use App\Models\Manager;
use App\Services\EmailSuppressionService;
use App\Services\MailService;
use App\Services\ManagerEmailVerificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class ManagerEmailVerificationServiceTest extends TestCase
{
    public function test_send_returns_false_for_suppressed_email(): void
    {
        try {
            if (! Schema::hasTable('email_delivery_suppressions') || ! Schema::hasTable('managers')) {
                $this->markTestSkipped('Required tables not available.');
            }
        } catch (\Throwable) {
            $this->markTestSkipped('Required tables not available.');
        }

        $email = 'verify-suppressed-'.uniqid().'@example.com';
        app(EmailSuppressionService::class)->addSuppression($email, 'hard_bounce', 'test');

        $manager = new Manager([
            'username' => 'tester',
            'email' => $email,
        ]);
        $manager->id = 999001;
        $manager->email_verified_at = null;

        $mail = Mockery::mock(MailService::class);
        $mail->shouldNotReceive('send');

        $service = new ManagerEmailVerificationService($mail);
        $this->assertFalse($service->send($manager));

        DB::table('email_delivery_suppressions')
            ->where('email_sha256', hash('sha256', strtolower(trim($email))))
            ->delete();
    }

    public function test_mark_verified_sets_timestamp(): void
    {
        try {
            if (! Schema::hasTable('managers') || ! Schema::hasTable('restaurants')) {
                $this->markTestSkipped('Required tables not available.');
            }
        } catch (\Throwable) {
            $this->markTestSkipped('Required tables not available.');
        }

        $restaurantId = DB::table('restaurants')->insertGetId([
            'name' => 'Verify Test '.uniqid(),
            'slug' => 'verify-test-'.uniqid(),
            'email' => 'r-'.uniqid().'@example.com',
            'is_active' => 1,
            'template_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managerId = DB::table('managers')->insertGetId([
            'username' => 'vfy_'.uniqid(),
            'email' => 'mgr-'.uniqid().'@example.com',
            'password_hash' => bcrypt('password123'),
            'restaurant_id' => $restaurantId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $manager = Manager::findOrFail($managerId);
        $this->assertNull($manager->email_verified_at);

        app(ManagerEmailVerificationService::class)->markVerified($manager);
        $manager->refresh();
        $this->assertNotNull($manager->email_verified_at);

        DB::table('managers')->where('id', $managerId)->delete();
        DB::table('restaurants')->where('id', $restaurantId)->delete();
    }
}
