<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminOnly
{
    /**
     * Handle an incoming request.
     * Only allow super admin users
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
