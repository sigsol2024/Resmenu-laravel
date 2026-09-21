<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Services\ManagerEmailVerificationService;
use App\Services\SiteSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerEmailVerificationController extends Controller
{
    /**
     * GET signed link → confirmation page (avoids email-scanner auto-verify).
     */
    public function show(Request $request, int $id, string $hash, ManagerEmailVerificationService $verification, SiteSettingsService $siteSettings)
    {
        if (! $request->hasValidSignature()) {
            return response()->view('auth.verify-email-invalid', [
                'siteName' => $siteSettings->siteName(),
                'message' => 'This verification link is invalid or has expired. Log in and use Resend on the banner.',
            ], 403);
        }

        $manager = Manager::find($id);
        if (! $manager || ! $verification->hashMatches($manager, $hash)) {
            return response()->view('auth.verify-email-invalid', [
                'siteName' => $siteSettings->siteName(),
                'message' => 'This verification link is invalid.',
            ], 403);
        }

        return view('auth.verify-email-confirm', [
            'siteName' => $siteSettings->siteName(),
            'id' => $id,
            'hash' => $hash,
            'signature' => $request->query('signature'),
            'expires' => $request->query('expires'),
            'alreadyVerified' => $verification->hasVerifiedEmail($manager),
        ]);
    }

    /**
     * POST confirmation with signed query params.
     */
    public function confirm(Request $request, ManagerEmailVerificationService $verification)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'hash' => 'required|string',
        ]);

        if (! $request->hasValidSignature()) {
            return redirect()
                ->route('login')
                ->with('error', 'This verification link is invalid or has expired. Log in and use Resend on the banner.');
        }

        $manager = Manager::findOrFail((int) $data['id']);

        if (! $verification->hashMatches($manager, (string) $data['hash'])) {
            return redirect()
                ->route('login')
                ->with('error', 'This verification link is invalid.');
        }

        $verification->markVerified($manager);

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        if (Auth::guard('manager')->check() && (int) Auth::guard('manager')->id() === (int) $manager->id) {
            return redirect()
                ->route('manager.dashboard')
                ->with('success', 'Your email has been verified. You can now manage your menu.');
        }

        return redirect()
            ->route('login')
            ->with('success', 'Your email has been verified. Please log in.');
    }

    public function resend(Request $request, ManagerEmailVerificationService $verification)
    {
        /** @var Manager $manager */
        $manager = Auth::guard('manager')->user();

        if ($verification->hasVerifiedEmail($manager)) {
            return back()->with('success', 'Your email is already verified.');
        }

        if (! $verification->send($manager)) {
            return back()->with('error', 'Could not send verification email. Check the address or try again later.');
        }

        return back()->with('success', 'Verification email sent. Check your inbox.');
    }
}
