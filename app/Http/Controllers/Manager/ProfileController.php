<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('manager.profile', [
            'manager' => Auth::guard('manager')->user(),
        ]);
    }

    public function update(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $data = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:'.config('resmenu.password_min_length', 8),
        ]);

        $manager->email = $data['email'];
        if (! empty($data['password'])) {
            $manager->password_hash = Hash::make($data['password']);
        }
        $manager->save();

        return back()->with('success', 'Profile updated.');
    }
}
