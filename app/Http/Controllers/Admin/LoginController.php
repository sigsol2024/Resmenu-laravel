<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show(Request $request)
    {
        return redirect()->route('login', $request->query());
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::query()
            ->where('username', $data['username'])
            ->orWhere('email', $data['username'])
            ->first();

        if (! $admin || ! Hash::check($data['password'], $admin->password_hash)) {
            return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
        }

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();
        session(['last_activity' => time()]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
