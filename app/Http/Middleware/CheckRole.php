<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role_id ? $user->role : '';

        if (($user->role_id === 1 || $user->role_id ===2 ) && in_array('admin', $roles)) {
            return $next($request);
        }

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        return redirect()->route('dashboard');
    }
}