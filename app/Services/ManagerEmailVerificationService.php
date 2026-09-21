<?php

namespace App\Services;

use App\Models\Manager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

class ManagerEmailVerificationService
{
    public function __construct(
        private MailService $mail,
        private PlatformMailTemplate $template,
    ) {}

    public function hasVerifiedEmail(Manager $manager): bool
    {
        return $manager->email_verified_at !== null;
    }

    public function markVerified(Manager $manager): void
    {
        if ($this->hasVerifiedEmail($manager)) {
            return;
        }

        $manager->forceFill(['email_verified_at' => now()])->save();
        $manager->refresh();

        // Keep the authenticated session in sync so the banner clears immediately.
        if (Auth::guard('manager')->check() && (int) Auth::guard('manager')->id() === (int) $manager->id) {
            Auth::guard('manager')->setUser($manager);
        }
    }

    /**
     * Send signed magic-link verification email (branded platform template).
     */
    public function send(Manager $manager): bool
    {
        if ($this->hasVerifiedEmail($manager)) {
            return true;
        }

        $email = (string) $manager->email;

        if (app(EmailSuppressionService::class)->isSuppressed($email)) {
            Log::info('Manager verification mail suppressed', ['manager_id' => $manager->id]);

            return false;
        }

        $emailKey = 'manager_verify_email:'.strtolower($email);
        $ipKey = 'manager_verify_ip:'.(string) request()->ip();
        $emailLimit = (int) config('resmenu.email_verify_limit_per_email', 5);
        $ipLimit = (int) config('resmenu.email_verify_limit_per_ip', 10);

        if (RateLimiter::tooManyAttempts($emailKey, $emailLimit) || RateLimiter::tooManyAttempts($ipKey, $ipLimit)) {
            return false;
        }

        // Count against quota on accepted dispatch (before send).
        $window = (int) config('resmenu.email_verify_window_seconds', 3600);
        RateLimiter::hit($emailKey, $window);
        RateLimiter::hit($ipKey, $window);

        $minutes = (int) config('resmenu.email_verify_ttl_minutes', 60);
        $url = URL::temporarySignedRoute(
            'manager.verification.verify',
            now()->addMinutes($minutes),
            [
                'id' => $manager->id,
                'hash' => sha1($email),
            ]
        );

        $name = e($manager->username);
        $safeUrl = e($url);
        $body = '<h2 style="margin:0 0 12px;font-size:22px;color:#111827;">Verify your email</h2>'
            .'<p style="margin:0 0 12px;">Hi '.$name.',</p>'
            .'<p style="margin:0 0 20px;">Confirm this email for your Resmenu manager account to unlock full menu management.</p>'
            .'<p style="margin:0 0 24px;text-align:center;">'
            .'<a href="'.$safeUrl.'" style="display:inline-block;background:#f97415;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:8px;font-weight:700;">Verify my email</a>'
            .'</p>'
            .'<p style="margin:0;color:#6b7280;font-size:13px;">This link expires in '.$minutes.' minutes. If you did not create this account, you can ignore this email.</p>';

        $html = $this->template->render('Verify your Resmenu email', $body);

        return $this->mail->send($email, (string) $manager->username, 'Verify your Resmenu email', $html);
    }

    public function hashMatches(Manager $manager, string $hash): bool
    {
        return hash_equals(sha1((string) $manager->email), $hash);
    }
}
