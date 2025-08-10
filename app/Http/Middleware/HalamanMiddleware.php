<?php

namespace App\Http\Middleware;

use App\Models\AksesHalaman;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HalamanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $slug): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $userRoles = Auth()->user()->aksesuser->pluck('id_akses')->toArray();

        $userHalaman = AksesHalaman::WhereIn('id_akses', $userRoles)->with('halaman')->whereHas('halaman', function($query) use ($slug) {
            $query->where('slug', $slug);
        })->pluck('id_akses')->toArray();

        if (!$userHalaman) {
            abort(403, "Anda tidak boleh mengakses halaman ini.");
        }

        $aksesDefault = session('akses_default_id');
        if (!$aksesDefault || !in_array($aksesDefault, $userHalaman)) {
            abort(403, 'Anda tidak boleh mengakses halaman ini.');
        }

        return $next($request);
    }
}
