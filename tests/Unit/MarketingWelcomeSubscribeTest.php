<?php

namespace Tests\Unit;

use App\Models\Manager;
use App\Services\CRM\ContactLeadService;
use App\Services\CRM\MarketingSubscribeFromEmailService;
use App\Services\EmailSuppressionService;
use App\Services\MailService;
use App\Services\ManagerWelcomeMailService;
use App\Services\PlatformMailTemplate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ViewErrorBag;
use Mockery;
use Tests\TestCase;

class MarketingWelcomeSubscribeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! config('app.key')) {
            config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
        }

        $suppression = Mockery::mock(EmailSuppressionService::class);
        $suppression->shouldReceive('isSuppressed')->andReturn(false);
        $this->app->instance(EmailSuppressionService::class, $suppression);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_welcome_mail_with_consent_explains_opt_in_without_cta(): void
    {
        $captured = [];
        $mail = Mockery::mock(MailService::class);
        $mail->shouldReceive('send')
            ->once()
            ->andReturnUsing(function ($to, $name, $subject, $html) use (&$captured) {
                $captured = compact('to', 'name', 'subject', 'html');

                return true;
            });

        $template = Mockery::mock(PlatformMailTemplate::class);
        $template->shouldReceive('render')->once()->andReturnUsing(fn ($title, $body) => $body);

        $service = new ManagerWelcomeMailService($mail, $template);
        $manager = new Manager(['id' => 42, 'username' => 'Chef', 'email' => 'chef@example.com']);
        $manager->id = 42;

        $this->assertTrue($service->send($manager, true));
        $this->assertSame('Welcome to Resmenu', $captured['subject']);
        $this->assertStringContainsString('opted in', $captured['html']);
        $this->assertStringNotContainsString('Subscribe to Resmenu Updates', $captured['html']);
    }

    public function test_welcome_mail_without_consent_includes_subscribe_cta(): void
    {
        $captured = [];
        $mail = Mockery::mock(MailService::class);
        $mail->shouldReceive('send')
            ->once()
            ->andReturnUsing(function ($to, $name, $subject, $html) use (&$captured) {
                $captured = compact('html');

                return true;
            });

        $template = Mockery::mock(PlatformMailTemplate::class);
        $template->shouldReceive('render')->once()->andReturnUsing(fn ($title, $body) => $body);

        $service = new ManagerWelcomeMailService($mail, $template);
        $manager = new Manager(['username' => 'Chef', 'email' => 'chef@example.com']);
        $manager->id = 99;

        $this->assertTrue($service->send($manager, false));
        $this->assertStringContainsString('Subscribe to Resmenu Updates', $captured['html']);
        $this->assertStringContainsString('marketing-subscribe', $captured['html']);
        $this->assertStringNotContainsString('opted in', $captured['html']);
    }

    public function test_subscribe_confirm_already_subscribed_is_idempotent(): void
    {
        $leads = Mockery::mock(ContactLeadService::class);
        $leads->shouldNotReceive('capture');

        $service = new MarketingSubscribeFromEmailService($leads);
        $manager = new Manager([
            'email' => 'already@example.com',
            'username' => 'Already',
            'marketing_consent' => true,
        ]);
        $manager->id = 7;
        $manager->exists = false;

        $result = $service->confirm($manager);

        $this->assertSame('already', $result['status']);
        $this->assertStringContainsString('already subscribed', strtolower($result['message']));
    }

    public function test_subscribe_confirm_success_requires_hubspot_marketing_subscribed(): void
    {
        $leads = Mockery::mock(ContactLeadService::class);
        $leads->shouldReceive('capture')
            ->once()
            ->andReturn([
                'lead_id' => 1,
                'deduped' => false,
                'sync' => [
                    'success' => true,
                    'marketing_subscribed' => true,
                    'message' => 'ok',
                ],
            ]);

        $service = new MarketingSubscribeFromEmailService($leads);
        $manager = Mockery::mock(Manager::class)->makePartial();
        $manager->shouldReceive('refresh')->never();
        $manager->id = 8;
        $manager->email = 'new@example.com';
        $manager->username = 'New';
        $manager->phone = '+2348000000000';
        $manager->marketing_consent = false;
        $manager->exists = false;

        $result = $service->confirm($manager);

        $this->assertSame('success', $result['status']);
    }

    public function test_subscribe_confirm_failure_when_hubspot_subscribe_fails(): void
    {
        $leads = Mockery::mock(ContactLeadService::class);
        $leads->shouldReceive('capture')
            ->once()
            ->andReturn([
                'lead_id' => 1,
                'deduped' => false,
                'sync' => [
                    'success' => true,
                    'marketing_subscribed' => false,
                    'message' => 'subscribe failed',
                ],
            ]);

        $service = new MarketingSubscribeFromEmailService($leads);
        $manager = Mockery::mock(Manager::class)->makePartial();
        $manager->shouldReceive('refresh')->never();
        $manager->id = 9;
        $manager->email = 'fail@example.com';
        $manager->username = 'Fail';
        $manager->phone = null;
        $manager->marketing_consent = false;
        $manager->exists = false;

        $result = $service->confirm($manager);

        $this->assertSame('failure', $result['status']);
        $this->assertStringContainsString('could not subscribe', strtolower($result['message']));
    }

    public function test_invalid_signed_subscribe_link_is_rejected(): void
    {
        $response = $this->get('/email/marketing-subscribe/1/'.sha1('x@example.com'));

        $response->assertStatus(403);
        $response->assertSee('invalid or has expired', false);
    }

    public function test_tampered_signed_subscribe_link_is_rejected(): void
    {
        URL::forceRootUrl(config('app.url') ?: 'http://localhost');

        $url = URL::temporarySignedRoute(
            'manager.marketing.subscribe.show',
            now()->addDay(),
            ['id' => 1, 'hash' => sha1('real@example.com')]
        );

        // Changing the signature value invalidates the signed URL without needing DB.
        $tampered = preg_replace('/([?&])signature=[^&]+/', '$1signature=deadbeef', $url);
        $this->assertNotNull($tampered);
        $this->assertNotSame($url, $tampered);

        $response = $this->get($tampered);
        $response->assertStatus(403);
        $response->assertSee('invalid or has expired', false);
    }

    public function test_register_view_consent_unchecked_by_default(): void
    {
        view()->share('errors', new ViewErrorBag([]));

        $html = view('auth.register', [
            'plans' => collect(),
            'plan' => null,
            'cycle' => 'monthly',
            'recaptchaSiteKey' => null,
            'siteName' => 'Resmenu',
            'siteLogoUrl' => null,
            'marketingHomeUrl' => 'https://resmenu.net/',
            'showcaseRestaurantLogos' => [],
            'marketingConsentText' => "Yes, I'd like to receive product updates.",
            'marketingConsentTextVersion' => 'v2',
        ])->render();

        $this->assertStringContainsString('marketing-consent-panel', $html);
        $this->assertStringContainsString('name="marketing_consent"', $html);
        $this->assertDoesNotMatchRegularExpression(
            '/name="marketing_consent"[^>]*\schecked/',
            $html
        );
    }
}
