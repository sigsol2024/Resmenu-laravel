<?php

namespace App\Http\Middleware;

use App\Models\Manager;
use App\Services\ManagerEmailVerificationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menu catalog write gate only — do not merge with subscription or suspension.
 */
class EnsureManagerEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Manager|null $manager */
        $manager = Auth::guard('manager')->user();

        if (! $manager || app(ManagerEmailVerificationService::class)->hasVerifiedEmail($manager)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Verify your email to manage your menu.',
            ], 403);
        }

        return redirect()
            ->route('manager.dashboard')
            ->with('error', 'Verify your email to manage your menu. Use Resend on the banner if needed.');
    }
}
