<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Admin bypass
        if ($user->role->name === 'Admin') {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($user->role->name === trim($role)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action. You do not have the required role.');
    }
}
