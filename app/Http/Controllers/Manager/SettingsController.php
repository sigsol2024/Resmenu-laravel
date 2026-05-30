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
        $activeTab = $request->query('tab', old('tab', session('tab', 'account')));
        if (! in_array($activeTab, ['account', 'restaurant', 'password'], true)) {
            $activeTab = 'account';
        }

        return view('manager.settings', [
            'manager' => $manager,
            'restaurant' => $restaurant,
            'activeTab' => $activeTab,
            'logoUrl' => $this->uploads->publicUrl('logos', $restaurant->logo),
            'heroUrl' => $this->uploads->publicUrl('heroes', $restaurant->hero_image),
        ]);
    }

    public function update(Request $request)
    {
        $manager = Auth::guard('manager')->user();
        $restaurant = Restaurant::findOrFail((int) $request->attributes->get('restaurant_id'));
        $action = $request->input('action', 'update_profile');
        $tab = $request->input('tab', match ($action) {
            'update_restaurant' => 'restaurant',
            'update_password' => 'password',
            default => 'account',
        });

        if ($action === 'update_restaurant') {
            return $this->updateRestaurant($request, $restaurant, $tab);
        }

        if ($action === 'update_password') {
            return $this->updatePassword($request, $manager, $tab);
        }

        return $this->updateProfile($request, $manager, $restaurant, $tab);
    }

    private function redirectToTab(string $tab, string $message): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('manager.settings.edit', ['tab' => $tab])->with('success', $message);
    }

    private function updateProfile(Request $request, Manager $manager, Restaurant $restaurant, string $tab)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $usernameTaken = Manager::query()
            ->where('id', '!=', $manager->id)
            ->where('username', $data['username'])
            ->exists();
        if ($usernameTaken) {
            return redirect()->route('manager.settings.edit', ['tab' => $tab])
                ->withErrors(['username' => 'Username is already taken by another manager.'])
                ->withInput();
        }

        $emailTaken = Manager::query()
            ->where('id', '!=', $manager->id)
            ->where('email', $data['email'])
            ->exists();
        if ($emailTaken) {
            return redirect()->route('manager.settings.edit', ['tab' => $tab])
                ->withErrors(['email' => 'Email is already taken by another manager.'])
                ->withInput();
        }

        $oldEmail = $manager->email;
        $manager->username = $data['username'];
        $manager->email = $data['email'];
        $manager->save();

        if ($restaurant->manager_email === $oldEmail) {
            $restaurant->update(['manager_email' => $data['email']]);
        }

        return $this->redirectToTab($tab, 'Account updated successfully');
    }

    private function updatePassword(Request $request, Manager $manager, string $tab)
    {
        $min = (int) config('resmenu.password_min_length', 8);

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', "min:{$min}"],
            'confirm_password' => ['required', 'string', "same:new_password"],
        ]);

        if (! Hash::check($request->input('current_password'), $manager->password_hash)) {
            return redirect()->route('manager.settings.edit', ['tab' => $tab])
                ->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $manager->password_hash = Hash::make($request->input('new_password'));
        $manager->save();

        return $this->redirectToTab($tab, 'Password updated successfully');
    }

    private function updateRestaurant(Request $request, Restaurant $restaurant, string $tab)
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
            } else {
                return redirect()->route('manager.settings.edit', ['tab' => $tab])
                    ->withErrors(['logo' => $result['message'] ?? 'Logo upload failed.'])
                    ->withInput();
            }
        }

        if ($request->hasFile('hero_image')) {
            $result = $this->uploads->storeImage($request->file('hero_image'), 'heroes');
            if ($result['success'] ?? false) {
                $this->uploads->delete('heroes', $restaurant->hero_image);
                $payload['hero_image'] = $result['filename'];
            } else {
                return redirect()->route('manager.settings.edit', ['tab' => $tab])
                    ->withErrors(['hero_image' => $result['message'] ?? 'Cover image upload failed.'])
                    ->withInput();
            }
        }

        $restaurant->update($payload);

        return $this->redirectToTab($tab, 'Restaurant details updated successfully');
    }
}
