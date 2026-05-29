<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class RegistrationOtpService
{
    public function send(string $email, ?string $ip = null): bool
    {
        $deliverability = app(EmailDeliverabilityService::class)->evaluateMx($email);
        if ($deliverability['state'] === 'permanent_bad') {
            return false;
        }
        if ($deliverability['state'] === 'transient_unavailable') {
            return false;
        }

        $emailKey = 'reg_otp_email:'.strtolower(trim($email));
        $ipKey = $ip ? 'reg_otp_ip:'.$ip : null;

        if (RateLimiter::tooManyAttempts($emailKey, (int) config('resmenu.reg_otp_limit_per_email', 3))) {
            return false;
        }
        if ($ipKey && RateLimiter::tooManyAttempts($ipKey, (int) config('resmenu.reg_otp_limit_per_ip', 5))) {
            return false;
        }

        $code = (string) random_int(100000, 999999);
        Cache::put($this->cacheKey($email), $code, now()->addMinutes(config('resmenu.reg_otp_ttl_minutes', 10)));

        $sent = app(MailService::class)->send(
            $email,
            '',
            'Your Resmenu verification code',
            '<p>Your verification code is: <strong>'.$code.'</strong></p>'
        );

        if ($sent) {
            RateLimiter::hit($emailKey, (int) config('resmenu.reg_otp_email_window_seconds', 3600));
            if ($ipKey) {
                RateLimiter::hit($ipKey, (int) config('resmenu.reg_otp_ip_window_seconds', 3600));
            }
        }

        return $sent;
    }

    public function verify(string $email, string $otp): bool
    {
        $expected = Cache::get($this->cacheKey($email));

        return $expected !== null && hash_equals((string) $expected, trim($otp));
    }

    private function cacheKey(string $email): string
    {
        return 'reg_otp:'.strtolower(trim($email));
    }
}
