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
    private const SESSION_KEY = 'manager_email_verify_pending';

    /**
     * GET signed link → confirmation page (avoids email-scanner auto-verify).
     * Stores a short-lived session ticket so POST confirm does not reuse the GET signature
     * (Laravel signatures are bound to the full URL path).
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

        if ($request->hasSession()) {
            $request->session()->put(self::SESSION_KEY, [
                'id' => (int) $id,
                'hash' => (string) $hash,
                'until' => now()->addMinutes(30)->getTimestamp(),
            ]);
        }

        return view('auth.verify-email-confirm', [
            'siteName' => $siteSettings->siteName(),
            'id' => $id,
            'hash' => $hash,
            'alreadyVerified' => $verification->hasVerifiedEmail($manager),
        ]);
    }

    /**
     * POST confirmation using the session ticket from a valid signed GET.
     */
    public function confirm(Request $request, ManagerEmailVerificationService $verification)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'hash' => 'required|string',
        ]);

        $id = (int) $data['id'];
        $hash = (string) $data['hash'];
        $pending = $request->session()->get(self::SESSION_KEY);

        $ticketOk = is_array($pending)
            && (int) ($pending['id'] ?? 0) === $id
            && hash_equals((string) ($pending['hash'] ?? ''), $hash)
            && (int) ($pending['until'] ?? 0) >= now()->getTimestamp();

        if (! $ticketOk) {
            return redirect()
                ->route('login')
                ->with('error', 'This verification link is invalid or has expired. Log in and use Resend on the banner.');
        }

        $manager = Manager::findOrFail($id);

        if (! $verification->hashMatches($manager, $hash)) {
            return redirect()
                ->route('login')
                ->with('error', 'This verification link is invalid.');
        }

        $verification->markVerified($manager);
        $request->session()->forget(self::SESSION_KEY);
        $manager->refresh();

        Auth::guard('manager')->login($manager);
        $request->session()->regenerate();

        return redirect()
            ->route('manager.dashboard')
            ->with('success', 'Your email has been verified. You can now manage your menu.');
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
