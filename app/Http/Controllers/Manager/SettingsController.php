<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $restaurant = Restaurant::findOrFail((int) $request->attributes->get('restaurant_id'));

        return view('manager.settings', [
            'manager' => $manager,
            'restaurant' => $restaurant,
        ]);
    }

    public function update(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $data = $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $exists = Manager::query()
            ->where('id', '!=', $manager->id)
            ->where(function ($q) use ($data) {
                $q->where('username', $data['username'])->orWhere('email', $data['email']);
            })->exists();

        if ($exists) {
            return back()->withErrors(['email' => 'Username or email already in use.'])->withInput();
        }

        $manager->username = $data['username'];
        $manager->email = $data['email'];
        if (! empty($data['password'])) {
            $manager->password_hash = Hash::make($data['password']);
        }
        $manager->save();

        return back()->with('success', 'Profile updated.');
    }
}
