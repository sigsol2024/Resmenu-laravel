<?php

namespace App\Support;

use App\Models\Admin;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Dual-guard admin→manager impersonation helpers.
 * Preserves impersonator_admin_id so the original admin can always be restored.
 */
final class ManagerImpersonation
{
    public static function active(Request $request): bool
    {
        return (bool) $request->session()->get('impersonating')
            && (int) $request->session()->get('impersonator_admin_id') > 0;
    }

    /**
     * Restore the original admin from session, or fully clear the session if restore fails.
     */
    public static function restoreAdmin(
        Request $request,
        ?string $successMessage = 'Returned to administrator.',
        ?string $stoppedReason = null,
    ): RedirectResponse {
        $adminId = (int) $request->session()->get('impersonator_admin_id');
        $manager = Auth::guard('manager')->user();
        $restaurantId = (int) ($manager?->restaurant_id ?? $request->session()->get('restaurant_id') ?? 0);
        $managerId = (int) ($manager?->id ?? 0);

        $admin = $adminId > 0 ? Admin::query()->find($adminId) : null;
        if (! $admin instanceof Admin) {
            Auth::guard('admin')->logout();
            Auth::guard('manager')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['impersonation' => 'Unable to restore administrator session.']);
        }

        Auth::guard('manager')->logout();
        Auth::guard('admin')->login($admin, false);

        $request->session()->forget(['impersonating', 'impersonator_admin_id', 'restaurant_id']);
        $request->session()->put([
            'user_role' => 'super_admin',
            'last_activity' => time(),
        ]);
        $request->session()->regenerate();

        try {
            app(ActivityLogService::class)->record(
                'admin',
                $adminId,
                'manager.impersonation.stopped',
                $restaurantId > 0 ? $restaurantId : null,
                $managerId > 0 ? 'manager' : null,
                $managerId > 0 ? $managerId : null,
                null,
                [
                    'impersonator_admin_id' => $adminId,
                    'target_manager_id' => $managerId > 0 ? $managerId : null,
                    'restaurant_id' => $restaurantId > 0 ? $restaurantId : null,
                    'reason' => $stoppedReason,
                ],
                $request->ip(),
                $request->userAgent()
            );
        } catch (\Throwable) {
            // Never block return-to-admin on audit failure.
        }

        $redirect = redirect()->route('admin.restaurants.index');
        if ($successMessage !== null && $successMessage !== '') {
            $redirect->with('success', $successMessage);
        }

        return $redirect;
    }
}
