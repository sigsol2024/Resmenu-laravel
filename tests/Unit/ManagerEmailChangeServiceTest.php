<?php

namespace Tests\Unit;

use App\Models\Manager;
use App\Services\MailService;
use App\Services\ManagerEmailChangeService;
use App\Services\ManagerEmailVerificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class ManagerEmailChangeServiceTest extends TestCase
{
    public function test_same_email_is_noop(): void
    {
        $manager = new Manager(['email' => 'same@example.com']);
        $manager->email_verified_at = now();

        $mail = Mockery::mock(MailService::class);
        $mail->shouldNotReceive('send');
        $verification = new ManagerEmailVerificationService($mail);
        $service = new ManagerEmailChangeService($verification);

        $result = $service->apply($manager, 'same@example.com');
        $this->assertFalse($result['changed']);
        $this->assertNotNull($manager->email_verified_at);
    }

    public function test_email_change_clears_verification_and_sends(): void
    {
        try {
            if (! Schema::hasTable('managers') || ! Schema::hasTable('restaurants')) {
                $this->markTestSkipped('Required tables not available.');
            }
        } catch (\Throwable) {
            $this->markTestSkipped('Required tables not available.');
        }

        $restaurantId = DB::table('restaurants')->insertGetId([
            'name' => 'Email Change',
            'slug' => 'email-change-'.uniqid(),
            'email' => 'r-'.uniqid().'@example.com',
            'is_active' => 1,
            'template_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managerId = DB::table('managers')->insertGetId([
            'username' => 'ech_'.uniqid(),
            'email' => 'old-'.uniqid().'@example.com',
            'password_hash' => bcrypt('password12345'),
            'restaurant_id' => $restaurantId,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $manager = Manager::findOrFail($managerId);
        $newEmail = 'new-'.uniqid().'@example.com';

        $mail = Mockery::mock(MailService::class);
        $mail->shouldReceive('send')->once()->andReturn(true);
        $this->app->instance(MailService::class, $mail);

        $result = app(ManagerEmailChangeService::class)->apply($manager, $newEmail);
        $manager->refresh();

        $this->assertTrue($result['changed']);
        $this->assertTrue($result['sent']);
        $this->assertSame($newEmail, $manager->email);
        $this->assertNull($manager->email_verified_at);

        DB::table('managers')->where('id', $managerId)->delete();
        DB::table('restaurants')->where('id', $restaurantId)->delete();
    }
}
