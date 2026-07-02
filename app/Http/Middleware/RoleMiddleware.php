<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $allowedRoles = array_map('trim', explode('|', $roles));

        if (!in_array(Auth::user()->role, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
