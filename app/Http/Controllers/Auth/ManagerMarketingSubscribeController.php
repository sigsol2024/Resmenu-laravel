<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Services\CRM\MarketingSubscribeFromEmailService;
use App\Services\ManagerWelcomeMailService;
use App\Services\SiteSettingsService;
use Illuminate\Http\Request;

class ManagerMarketingSubscribeController extends Controller
{
    private const SESSION_KEY = 'manager_marketing_subscribe_pending';

    private function siteName(SiteSettingsService $siteSettings): string
    {
        try {
            return $siteSettings->siteName();
        } catch (\Throwable) {
            return (string) config('app.name', 'Resmenu');
        }
    }

    /**
     * GET signed link → confirmation page (avoids email-scanner auto-subscribe).
     */
    public function show(
        Request $request,
        int $id,
        string $hash,
        ManagerWelcomeMailService $welcomeMail,
        SiteSettingsService $siteSettings,
    ) {
        if (! $request->hasValidSignature()) {
            return response()->view('auth.marketing-subscribe-invalid', [
                'siteName' => $this->siteName($siteSettings),
                'message' => 'This subscription link is invalid or has expired. If you still want updates, contact support or register again and use a fresh welcome email link.',
            ], 403);
        }

        $manager = null;
        try {
            $manager = Manager::find($id);
        } catch (\Throwable) {
            $manager = null;
        }

        if (! $manager || ! $welcomeMail->hashMatches($manager, $hash)) {
            return response()->view('auth.marketing-subscribe-invalid', [
                'siteName' => $this->siteName($siteSettings),
                'message' => 'This subscription link is invalid.',
            ], 403);
        }

        if ($request->hasSession()) {
            $request->session()->put(self::SESSION_KEY, [
                'id' => (int) $id,
                'hash' => (string) $hash,
                'until' => now()->addMinutes(30)->getTimestamp(),
            ]);
        }

        return view('auth.marketing-subscribe-confirm', [
            'siteName' => $this->siteName($siteSettings),
            'id' => $id,
            'hash' => $hash,
            'alreadySubscribed' => (bool) $manager->marketing_consent,
        ]);
    }

    /**
     * POST confirmation using the session ticket from a valid signed GET.
     */
    public function confirm(
        Request $request,
        ManagerWelcomeMailService $welcomeMail,
        MarketingSubscribeFromEmailService $subscribe,
        SiteSettingsService $siteSettings,
    ) {
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
            return response()->view('auth.marketing-subscribe-invalid', [
                'siteName' => $this->siteName($siteSettings),
                'message' => 'This subscription link is invalid or has expired. Open the link from your welcome email again to confirm.',
            ], 403);
        }

        $manager = null;
        try {
            $manager = Manager::find($id);
        } catch (\Throwable) {
            $manager = null;
        }

        if (! $manager || ! $welcomeMail->hashMatches($manager, $hash)) {
            return response()->view('auth.marketing-subscribe-invalid', [
                'siteName' => $this->siteName($siteSettings),
                'message' => 'This subscription link is invalid.',
            ], 403);
        }

        $result = $subscribe->confirm($manager, $request);
        $request->session()->forget(self::SESSION_KEY);

        return view('auth.marketing-subscribe-result', [
            'siteName' => $this->siteName($siteSettings),
            'status' => $result['status'],
            'message' => $result['message'],
        ]);
    }
}
