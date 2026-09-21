<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        /** @var Admin $admin */
        $admin = $request->user('admin');

        return view('admin.profile.show', [
            'admin' => $admin,
        ]);
    }

    public function update(Request $request)
    {
        /** @var Admin $admin */
        $admin = $request->user('admin');

        $action = $request->input('action', 'update_profile');

        if ($action === 'update_password') {
            return $this->updatePassword($request, $admin);
        }

        // Username is immutable — only email may change here.
        // Ignore any username / privilege fields in the payload.
        $data = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id),
            ],
        ]);

        $admin->forceFill([
            'email' => $data['email'],
        ])->save();

        return back()->with('success', 'Profile updated.');
    }

    private function updatePassword(Request $request, Admin $admin)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        if (! Hash::check($request->input('current_password'), $admin->password_hash)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $admin->forceFill([
            'password_hash' => Hash::make($request->input('new_password')),
        ])->save();

        $request->session()->regenerate();
        $request->session()->put('last_activity', time());

        return back()->with('success', 'Password updated.');
    }
}
