<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Manager;
use App\Services\SiteSettingsService;
use App\Support\SafeRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show(Request $request, SiteSettingsService $siteSettings)
    {
        if (Auth::guard('manager')->check()) {
            return redirect()->to($this->postLoginPath($request, 'manager'));
        }
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        $settings = $siteSettings;

        return view('auth.login', [
            'next' => SafeRedirect::localPath($request->query('next')),
            'plan' => $request->query('plan'),
            'cycle' => $request->query('cycle'),
            'registerUrl' => route('register', array_filter($request->only(['plan', 'cycle', 'next']))),
            'siteName' => $settings->siteName(),
            'siteLogoUrl' => $settings->siteLogoUrl(),
            'marketingHomeUrl' => 'https://resmenu.net/',
            'showcaseRestaurantLogos' => $settings->showcaseRestaurantLogos(),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'next' => ['nullable', 'string'],
        ]);

        $login = $credentials['username'];
        $password = $credentials['password'];
        $next = SafeRedirect::localPath($credentials['next'] ?? $request->query('next'));

        $admin = Admin::query()->where('username', $login)->orWhere('email', $login)->first();
        if ($admin && Hash::check($password, $admin->password_hash)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            session(['last_activity' => time(), 'user_role' => 'super_admin']);

            return redirect()->to($next ?: route('admin.dashboard'));
        }

        $manager = Manager::query()->where('username', $login)->orWhere('email', $login)->first();
        if ($manager && Hash::check($password, $manager->password_hash)) {
            Auth::guard('manager')->login($manager);
            $request->session()->regenerate();
            session([
                'last_activity' => time(),
                'user_role' => 'manager',
                'restaurant_id' => $manager->restaurant_id,
            ]);

            return redirect()->to($next ?: route('manager.dashboard'));
        }

        return back()->withErrors(['username' => 'Invalid username or password.'])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('manager')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function postLoginPath(Request $request, string $guard): string
    {
        $next = SafeRedirect::localPath($request->query('next'));
        if ($next !== '') {
            return $next;
        }

        return $guard === 'admin' ? route('admin.dashboard') : route('manager.dashboard');
    }
}
