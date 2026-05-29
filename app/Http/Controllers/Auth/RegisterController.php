<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\DisposableEmailService;
use App\Services\EmailDeliverabilityService;
use App\Services\RecaptchaService;
use App\Services\RegistrationOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __construct(
        private RegistrationOtpService $otp,
        private RecaptchaService $recaptcha,
        private DisposableEmailService $disposableEmail,
    ) {}

    public function show(Request $request)
    {
        if (Auth::guard('manager')->check()) {
            return redirect()->route('manager.dashboard');
        }

        return view('auth.register', [
            'plans' => SubscriptionPlan::query()->where('is_active', 1)->orderBy('display_order')->get(),
            'plan' => $request->query('plan'),
            'cycle' => $request->query('cycle', 'monthly'),
            'recaptchaSiteKey' => config('resmenu.recaptcha_site_key'),
        ]);
    }

    public function sendOtp(Request $request)
    {
        if (! $this->recaptcha->verifyRequest($request)) {
            return response()->json(['success' => false, 'message' => 'Captcha verification failed.'], 422);
        }

        $data = $request->validate(['email' => 'required|email']);
        if ($this->disposableEmail->isDisposable($data['email'])) {
            return response()->json(['success' => false, 'message' => 'Please use a permanent email address.'], 422);
        }
        $mx = app(EmailDeliverabilityService::class)->evaluateMx($data['email']);
        if ($mx['state'] === 'permanent_bad') {
            return response()->json(['success' => false, 'message' => 'That email domain cannot receive mail. Use a different address.'], 422);
        }
        if ($mx['state'] === 'transient_unavailable') {
            return response()->json(['success' => false, 'message' => 'We could not verify your email domain. Try again in a few minutes.'], 503);
        }
        if (! $this->otp->send($data['email'], $request->ip())) {
            return response()->json(['success' => false, 'message' => 'Could not send verification code. Check the address or try again later.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Verification code sent.']);
    }

    public function store(Request $request)
    {
        if (! $this->recaptcha->verifyRequest($request)) {
            return back()->withErrors(['captcha' => 'Captcha verification failed. Please try again.'])->withInput();
        }

        $data = $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'username' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:'.config('resmenu.password_min_length', 8),
            'otp' => 'required|string|size:6',
            'plan_id' => 'nullable|integer',
        ]);

        if ($this->disposableEmail->isDisposable($data['email'])) {
            return back()->withErrors(['email' => 'Please use a permanent email address.'])->withInput();
        }

        if (! $this->otp->verify($data['email'], $data['otp'])) {
            return back()->withErrors(['otp' => 'Invalid or expired verification code.'])->withInput();
        }

        $slug = Str::slug($data['restaurant_name']);
        if (Restaurant::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::lower(Str::random(4));
        }

        DB::transaction(function () use ($data, $slug) {
            $restaurant = Restaurant::create([
                'name' => $data['restaurant_name'],
                'slug' => $slug,
                'email' => $data['email'],
                'is_active' => 1,
                'template_id' => 4,
            ]);

            $manager = Manager::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password_hash' => Hash::make($data['password']),
                'restaurant_id' => $restaurant->id,
            ]);

            $planId = $data['plan_id'] ?? SubscriptionPlan::query()->where('is_active', 1)->orderBy('display_order')->value('id');
            if ($planId) {
                Subscription::create([
                    'restaurant_id' => $restaurant->id,
                    'plan_id' => $planId,
                    'billing_cycle' => 'monthly',
                    'status' => 'pending',
                ]);
            }

            Auth::guard('manager')->login($manager);
            session([
                'last_activity' => time(),
                'user_role' => 'manager',
                'restaurant_id' => $restaurant->id,
            ]);
        });

        return redirect()->route('manager.dashboard')->with('success', 'Account created.');
    }
}
