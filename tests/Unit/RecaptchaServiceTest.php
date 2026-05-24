<?php

namespace Tests\Unit;

use App\Services\RecaptchaService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecaptchaServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        $this->app->detectEnvironment(fn () => 'testing');
        parent::tearDown();
    }

    public function test_recaptcha_rejects_empty_token_when_enforced_and_configured(): void
    {
        app()->detectEnvironment(fn () => 'production');

        config([
            'resmenu.recaptcha_site_key' => 'site',
            'resmenu.recaptcha_secret_key' => 'secret',
        ]);

        $service = app(RecaptchaService::class);
        $this->assertFalse($service->verifyToken(''));
    }

    public function test_recaptcha_accepts_valid_google_response_when_enforced(): void
    {
        app()->detectEnvironment(fn () => 'production');

        config([
            'resmenu.recaptcha_site_key' => 'site',
            'resmenu.recaptcha_secret_key' => 'secret',
        ]);

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true], 200),
        ]);

        $service = app(RecaptchaService::class);
        $this->assertTrue($service->verifyToken('valid-token'));
    }
}
