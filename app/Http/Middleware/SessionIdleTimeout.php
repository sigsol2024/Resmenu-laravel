<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionIdleTimeout
{
    public function handle(Request $request, Closure $next, string $guard = 'manager'): Response
    {
        $idle = (int) config('resmenu.auth_session_idle_seconds', 3600);
        if ($idle > 0 && Auth::guard($guard)->check()) {
            $last = (int) session('last_activity', 0);
            if ($last > 0 && (time() - $last) > $idle) {
                Auth::guard($guard)->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route($guard === 'admin' ? 'admin.login' : 'login')
                    ->with('error', 'Your session expired due to inactivity.');
            }
            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}
