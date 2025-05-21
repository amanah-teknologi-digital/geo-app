<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $roles): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $userRoles = Auth()->user()->aksesuser->pluck('id_akses')->toArray();

        // Cek apakah ada intersection antara akses user dan yang di middleware
        $hasRole = count(array_intersect($userRoles, $roles)) > 0;

        if (!$hasRole) {
            abort(403, 'Unauthorized');
        }

        $aksesDefault = session('akses_default_id');
        if (!$aksesDefault || !in_array($aksesDefault, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
