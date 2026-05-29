<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function __construct(private UploadService $uploads) {}

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
        $restaurant = Restaurant::findOrFail((int) $request->attributes->get('restaurant_id'));
        $action = $request->input('action', 'update_profile');

        if ($action === 'update_restaurant') {
            return $this->updateRestaurant($request, $restaurant);
        }

        if ($action === 'update_password') {
            return $this->updatePassword($request, $manager);
        }

        return $this->updateProfile($request, $manager, $restaurant);
    }

    private function updateProfile(Request $request, Manager $manager, Restaurant $restaurant)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $exists = Manager::query()
            ->where('id', '!=', $manager->id)
            ->where(function ($q) use ($data) {
                $q->where('username', $data['username'])->orWhere('email', $data['email']);
            })->exists();

        if ($exists) {
            return back()->withErrors(['email' => 'Username or email already in use.'])->withInput();
        }

        $oldEmail = $manager->email;
        $manager->username = $data['username'];
        $manager->email = $data['email'];
        $manager->save();

        if ($restaurant->email === $oldEmail || $restaurant->manager_email === $oldEmail) {
            $restaurant->update(['email' => $data['email'], 'manager_email' => $data['email']]);
        }

        return back()->with('success', 'Account updated.');
    }

    private function updatePassword(Request $request, Manager $manager)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:'.config('resmenu.password_min_length', 8), 'confirmed'],
        ]);

        $manager->password_hash = Hash::make($data['password']);
        $manager->save();

        return back()->with('success', 'Password updated.');
    }

    private function updateRestaurant(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'whatsapp_link' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|string|max:255',
            'twitter_url' => 'nullable|string|max:255',
            'footer_content' => 'nullable|string',
        ]);

        $payload = $data;

        if ($request->hasFile('logo')) {
            $result = $this->uploads->storeImage($request->file('logo'), 'logos');
            if ($result['success'] ?? false) {
                $this->uploads->delete('logos', $restaurant->logo);
                $payload['logo'] = $result['filename'];
            }
        }

        if ($request->hasFile('hero_image')) {
            $result = $this->uploads->storeImage($request->file('hero_image'), 'heroes');
            if ($result['success'] ?? false) {
                $this->uploads->delete('heroes', $restaurant->hero_image);
                $payload['hero_image'] = $result['filename'];
            }
        }

        $restaurant->update($payload);

        return back()->with('success', 'Restaurant profile updated.');
    }
}
