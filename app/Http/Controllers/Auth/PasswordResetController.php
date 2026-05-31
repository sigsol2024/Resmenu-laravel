<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Services\MailService;
use App\Services\RecaptchaService;
use App\Services\SiteSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(
        private RecaptchaService $recaptcha,
        private MailService $mail,
    ) {}

    public function showForgot(): View
    {
        return view('auth.forgot-password', [
            'recaptchaSiteKey' => config('resmenu.recaptcha_site_key'),
        ]);
    }

    public function sendReset(Request $request)
    {
        if (! $this->recaptcha->verifyRequest($request)) {
            return back()->withErrors(['captcha' => 'Captcha verification failed. Please try again.']);
        }

        $request->validate(['email' => 'required|email']);

        $email = Str::lower(trim((string) $request->email));
        $ipKey = 'pwd-reset-ip:'.$request->ip();
        $emailKey = 'pwd-reset-email:'.$email;

        if (RateLimiter::tooManyAttempts($ipKey, 5) || RateLimiter::tooManyAttempts($emailKey, 3)) {
            return back()->withErrors(['email' => 'Too many reset attempts. Please try again later.']);
        }

        RateLimiter::hit($ipKey, 3600);
        RateLimiter::hit($emailKey, 3600);

        $manager = Manager::where('email', $email)->first();
        if ($manager) {
            $token = Str::random(64);
            Cache::put('pwd_reset:'.$token, $manager->id, now()->addHour());

            $resetUrl = route('password.reset', ['token' => $token]);
            $html = view('emails.password-reset', [
                'name' => $manager->username ?: $manager->email,
                'resetUrl' => $resetUrl,
            ])->render();

            $this->mail->send(
                $manager->email,
                $manager->username ?: $manager->email,
                'Reset your Resmenu password',
                $html,
            );
        }

        return back()->with('success', 'If that email exists, we sent reset instructions.');
    }

    public function showReset(string $token, SiteSettingsService $siteSettings)
    {
        if (! Cache::has('pwd_reset:'.$token)) {
            abort(404);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'siteName' => $siteSettings->siteName(),
            'siteLogoUrl' => $siteSettings->siteLogoUrl(),
            'marketingHomeUrl' => 'https://resmenu.net/',
        ]);
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:'.config('resmenu.password_min_length', 8).'|confirmed',
        ]);

        $managerId = Cache::pull('pwd_reset:'.$data['token']);
        if (! $managerId) {
            return back()->withErrors(['password' => 'Reset link expired.']);
        }

        $manager = Manager::findOrFail($managerId);
        $manager->password_hash = Hash::make($data['password']);
        $manager->save();

        $this->invalidateManagerSessions((int) $manager->id);

        return redirect()->route('login')->with('success', 'Password updated. You can sign in.');
    }

    private function invalidateManagerSessions(int $managerId): void
    {
        if (! Schema::hasTable('sessions')) {
            return;
        }

        if (Schema::hasColumn('sessions', 'user_id')) {
            DB::table('sessions')->where('user_id', $managerId)->delete();

            return;
        }

        DB::table('sessions')
            ->where('payload', 'like', '%login_manager_%')
            ->where('payload', 'like', '%i:'.$managerId.';%')
            ->delete();
    }
}
