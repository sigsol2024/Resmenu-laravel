<?php

namespace App\Services;

use App\Models\Manager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

class ManagerEmailVerificationService
{
    public function __construct(private MailService $mail) {}

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
    }

    /**
     * Send signed magic-link verification email.
     * Reuses EmailSuppressionService via early check + MailService (no second suppression system).
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

        // Count against quota on accepted dispatch (before send) so queue/mail failures cannot bypass limits.
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
        $html = '<p>Hi '.$name.',</p>'
            .'<p>Please verify your email address for your Resmenu manager account by clicking the link below:</p>'
            .'<p><a href="'.$safeUrl.'">Verify my email</a></p>'
            .'<p>This link expires in '.$minutes.' minutes. If you did not create this account, you can ignore this email.</p>';

        return $this->mail->send($email, (string) $manager->username, 'Verify your Resmenu email', $html);
    }

    public function hashMatches(Manager $manager, string $hash): bool
    {
        return hash_equals(sha1((string) $manager->email), $hash);
    }
}
