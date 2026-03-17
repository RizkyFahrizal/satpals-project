<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    /**
     * Handle an incoming request.
     * Only allow users with admin access (super_admin or pengurus)
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->hasAdminAccess()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Anda tidak memiliki akses ke halaman admin.']);
        }

        return $next($request);
    }
}
