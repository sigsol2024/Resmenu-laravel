<?php

namespace App\Services;

use App\Models\Manager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

/**
 * Transactional welcome email after registration (separate from email verification).
 */
class ManagerWelcomeMailService
{
    public function __construct(
        private MailService $mail,
        private PlatformMailTemplate $template,
    ) {}

    public function send(Manager $manager, bool $marketingConsent): bool
    {
        $email = (string) $manager->email;

        if (app(EmailSuppressionService::class)->isSuppressed($email)) {
            Log::info('Manager welcome mail suppressed', ['manager_id' => $manager->id]);

            return false;
        }

        $name = e($manager->username);
        $body = '<h2 style="margin:0 0 12px;font-size:22px;color:#111827;">Welcome to Resmenu</h2>'
            .'<p style="margin:0 0 12px;">Hi '.$name.',</p>'
            .'<p style="margin:0 0 16px;">Your account has been created successfully. You can start setting up your digital menu right away.</p>';

        if ($marketingConsent) {
            $body .= '<p style="margin:0 0 16px;">Because you opted in to receive Resmenu marketing communications, we\'ll also send you occasional product updates, news, offers and other relevant information. You can manage your email preferences anytime via the unsubscribe link in those messages.</p>';
        } else {
            $subscribeUrl = $this->signedSubscribeUrl($manager);
            $safeUrl = e($subscribeUrl);
            $body .= '<p style="margin:0 0 12px;">Want to hear from us?</p>'
                .'<p style="margin:0 0 20px;">You can subscribe to Resmenu news, product updates, offers and other useful information.</p>'
                .'<p style="margin:0 0 24px;text-align:center;">'
                .'<a href="'.$safeUrl.'" style="display:inline-block;background:#f97415;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:8px;font-weight:700;">Subscribe to Resmenu Updates</a>'
                .'</p>'
                .'<p style="margin:0;color:#6b7280;font-size:13px;">This link expires in '.(int) config('resmenu.marketing_subscribe_ttl_days', 7).' days. Registering does not subscribe you to marketing by itself.</p>';
        }

        $html = $this->template->render('Welcome to Resmenu', $body);

        return $this->mail->send($email, (string) $manager->username, 'Welcome to Resmenu', $html);
    }

    public function signedSubscribeUrl(Manager $manager): string
    {
        $days = max(1, (int) config('resmenu.marketing_subscribe_ttl_days', 7));

        return URL::temporarySignedRoute(
            'manager.marketing.subscribe.show',
            now()->addDays($days),
            [
                'id' => $manager->id,
                'hash' => sha1((string) $manager->email),
            ]
        );
    }

    public function hashMatches(Manager $manager, string $hash): bool
    {
        return hash_equals(sha1((string) $manager->email), $hash);
    }
}
