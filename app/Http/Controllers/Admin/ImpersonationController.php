<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Manager;
use App\Models\Restaurant;
use App\Services\ActivityLogService;
use App\Support\ManagerImpersonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(Request $request, Restaurant $restaurant, ActivityLogService $activityLog)
    {
        $admin = Auth::guard('admin')->user();
        if (! $admin instanceof Admin) {
            abort(403);
        }

        if (ManagerImpersonation::active($request)) {
            return redirect()
                ->route('admin.restaurants.index')
                ->withErrors(['impersonation' => 'Already impersonating a manager. Return to Admin first.']);
        }

        if ($restaurant->isSuspended() || ! $restaurant->is_active) {
            return redirect()
                ->route('admin.restaurants.index')
                ->withErrors(['impersonation' => 'Cannot login as manager for a suspended or inactive restaurant.']);
        }

        $manager = Manager::query()->where('restaurant_id', $restaurant->id)->first();
        if (! $manager) {
            return redirect()
                ->route('admin.restaurants.index', ['edit' => $restaurant->id])
                ->withErrors(['impersonation' => 'This restaurant has no manager account. Create one via Edit first.']);
        }

        $adminId = (int) $admin->id;

        // Store admin identity before swapping guards.
        $request->session()->put([
            'impersonating' => true,
            'impersonator_admin_id' => $adminId,
        ]);

        Auth::guard('admin')->logout();
        Auth::guard('manager')->login($manager, false);

        $request->session()->put([
            'user_role' => 'manager',
            'restaurant_id' => (int) $manager->restaurant_id,
            'last_activity' => time(),
            'impersonating' => true,
            'impersonator_admin_id' => $adminId,
        ]);

        $request->session()->regenerate();

        // Re-assert after regenerate so a guard logout quirk cannot drop the restore keys.
        $request->session()->put([
            'impersonating' => true,
            'impersonator_admin_id' => $adminId,
            'user_role' => 'manager',
            'restaurant_id' => (int) $manager->restaurant_id,
            'last_activity' => time(),
        ]);

        try {
            $activityLog->record(
                'admin',
                $adminId,
                'manager.impersonation.started',
                (int) $restaurant->id,
                'manager',
                (int) $manager->id,
                null,
                [
                    'impersonator_admin_id' => $adminId,
                    'target_manager_id' => (int) $manager->id,
                    'restaurant_id' => (int) $restaurant->id,
                ],
                $request->ip(),
                $request->userAgent()
            );
        } catch (\Throwable) {
            // Impersonation must succeed even if audit insert fails.
        }

        return redirect()
            ->route('manager.dashboard')
            ->with('success', 'You are now viewing as '.$restaurant->name.'.');
    }

    public function leave(Request $request)
    {
        if (! ManagerImpersonation::active($request)) {
            return redirect()->route('login')->withErrors(['impersonation' => 'No active impersonation session.']);
        }

        return ManagerImpersonation::restoreAdmin($request, 'Returned to administrator.', 'manual_leave');
    }
}
