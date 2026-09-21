<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Services\ManagerEmailChangeService;
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

    public function update(Request $request, ManagerEmailChangeService $emailChange)
    {
        /** @var Manager $manager */
        $manager = Auth::guard('manager')->user();
        $data = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:'.config('resmenu.password_min_length', 8),
        ]);

        $emailTaken = Manager::query()
            ->where('id', '!=', $manager->id)
            ->where('email', strtolower(trim($data['email'])))
            ->exists();
        if ($emailTaken) {
            return back()->withErrors(['email' => 'Email is already taken by another manager.'])->withInput();
        }

        $result = $emailChange->apply($manager, $data['email']);
        $manager->refresh();

        if (! empty($data['password'])) {
            $manager->password_hash = Hash::make($data['password']);
            $manager->save();
        }

        $message = 'Profile updated.';
        if ($result['changed']) {
            $message = $result['sent']
                ? 'Email updated. Please verify your new address — a link was sent.'
                : 'Email updated. Verification is required; we could not send the email — use Resend on the banner.';
        }

        return back()->with('success', $message);
    }
}
