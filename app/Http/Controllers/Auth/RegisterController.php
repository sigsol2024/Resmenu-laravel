<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\SubscriptionPlan;
use App\Services\CRM\ContactLeadService;
use App\Services\DisposableEmailService;
use App\Services\EmailDeliverabilityService;
use App\Services\ManagerEmailVerificationService;
use App\Services\RecaptchaService;
use App\Services\SiteSettingsService;
use App\Services\SubscriptionService;
use App\Support\PhoneNormalizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function __construct(
        private RecaptchaService $recaptcha,
        private DisposableEmailService $disposableEmail,
    ) {}

    public function show(Request $request, SiteSettingsService $siteSettings)
    {
        if (Auth::guard('manager')->check()) {
            return redirect()->route('manager.dashboard');
        }

        return view('auth.register', [
            'plans' => SubscriptionPlan::query()->where('is_active', 1)->orderBy('display_order')->get(),
            'plan' => $request->query('plan'),
            'cycle' => $request->query('cycle', 'monthly'),
            'recaptchaSiteKey' => config('resmenu.recaptcha_site_key'),
            'siteName' => $siteSettings->siteName(),
            'siteLogoUrl' => $siteSettings->siteLogoUrl(),
            'marketingHomeUrl' => 'https://resmenu.net/',
            'showcaseRestaurantLogos' => $siteSettings->showcaseRestaurantLogos(),
            'marketingConsentText' => config('resmenu.marketing_consent_text'),
            'marketingConsentTextVersion' => config('resmenu.marketing_consent_text_version'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:managers,username',
            'email' => 'required|email|max:255|unique:managers,email',
            'phone' => 'required|string|max:40',
            'password' => 'required|string|min:'.config('resmenu.password_min_length', 8),
            'password_confirm' => 'required|same:password',
            'marketing_consent' => 'nullable|boolean',
            'marketing_consent_text_version' => 'nullable|string|max:32',
            'register_step' => 'nullable|integer|min:1|max:3',
        ]);

        if (! $this->recaptcha->verifyRequest($request)) {
            return back()
                ->withErrors(['captcha' => 'Captcha verification failed. Please try again.'])
                ->withInput($request->except(['password', 'password_confirm', 'g-recaptcha-response']) + ['register_step' => 3]);
        }

        if ($this->disposableEmail->isDisposable($data['email'])) {
            return back()
                ->withErrors(['email' => 'Please use a permanent email address.'])
                ->withInput($request->except(['password', 'password_confirm']) + ['register_step' => 2]);
        }

        $mx = app(EmailDeliverabilityService::class)->evaluateMx($data['email']);
        if ($mx['state'] === 'permanent_bad') {
            return back()
                ->withErrors(['email' => 'That email domain cannot receive mail. Use a different address.'])
                ->withInput($request->except(['password', 'password_confirm']) + ['register_step' => 2]);
        }
        if ($mx['state'] === 'transient_unavailable') {
            return back()
                ->withErrors(['email' => 'We could not verify your email domain. Try again in a few minutes.'])
                ->withInput($request->except(['password', 'password_confirm']) + ['register_step' => 2]);
        }

        $slug = Str::slug($data['restaurant_name']);
        if ($slug === '' || Restaurant::where('slug', $slug)->exists()) {
            $slug = ($slug !== '' ? $slug : 'restaurant').'-'.Str::lower(Str::random(4));
        }

        $phone = PhoneNormalizer::normalize($data['phone']);
        if ($phone === null) {
            throw ValidationException::withMessages([
                'phone' => 'Enter a valid phone number (7–15 digits, optional leading +).',
            ]);
        }

        $marketingConsent = $request->boolean('marketing_consent');
        // Server-owned consent text version — ignore browser field.
        $consentVersion = (string) config('resmenu.marketing_consent_text_version', 'v1');

        /** @var Manager|null $manager */
        $manager = null;

        DB::transaction(function () use ($data, $slug, $request, $marketingConsent, $consentVersion, $phone, &$manager) {
            $restaurant = Restaurant::create([
                'name' => $data['restaurant_name'],
                'slug' => $slug,
                'email' => $data['email'],
                'is_active' => 1,
                'template_id' => 4,
                'last_activity_at' => now(),
            ]);

            $manager = Manager::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'phone' => $phone,
                'password_hash' => Hash::make($data['password']),
                'restaurant_id' => $restaurant->id,
                'marketing_consent' => $marketingConsent,
                'marketing_consent_at' => $marketingConsent ? now() : null,
                'marketing_consent_source' => $marketingConsent ? 'registration' : null,
                'marketing_consent_text_version' => $marketingConsent ? $consentVersion : null,
            ]);

            app(SubscriptionService::class)->startTrialForRestaurant($restaurant->id);

            Auth::guard('manager')->login($manager);
            $request->session()->regenerate();
            session([
                'last_activity' => time(),
                'user_role' => 'manager',
                'restaurant_id' => $restaurant->id,
            ]);

            try {
                app(\App\Services\RestaurantLifecycleService::class)->recordManagerLogin($manager);
            } catch (\Throwable) {
                // Non-fatal — registration should still succeed.
            }
        });

        try {
            app(ManagerEmailVerificationService::class)->send($manager);
        } catch (\Throwable $e) {
            Log::warning('Registration verification email failed: '.$e->getMessage());
        }

        try {
            app(ContactLeadService::class)->capture([
                'email' => $manager->email,
                'name' => $manager->username,
                'phone' => $manager->phone,
                'source' => 'registration',
                'marketing_consent' => $marketingConsent,
                'marketing_consent_text_version' => $consentVersion,
                'manager_id' => $manager->id,
            ], $request);
        } catch (\Throwable $e) {
            Log::warning('Registration CRM sync failed: '.$e->getMessage());
        }

        return redirect()->route('manager.billing.index', ['welcome' => 1]);
    }
}
