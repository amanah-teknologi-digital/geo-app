<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DefaultDashboardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $defaultUser = session('akses_default_id');

        if (!($defaultUser)) {
            // Set default role
            $user = Auth::user();
            // Load akses secara aman
            $aksesList = $user->aksesuser;
            $defaultAkses = $aksesList->firstWhere('is_default', 1) ?? $aksesList->first();

            if (!$defaultAkses) {
                abort(403, 'Access Denied');
            }

            $dataHalaman = $defaultAkses->akses->akseshalaman;
            $defaultRoute = $defaultAkses->akses->halaman->url;

            session([
                'akses_default_id' => $defaultAkses->id_akses,
                'akses_default_nama' => $defaultAkses->akses->nama,
                'akses_default_halaman' => $dataHalaman,
                'akses_default_halaman_route' => $defaultAkses->akses->halaman->url
            ]);
        }else{
            $defaultRoute = session('akses_default_halaman_route');
        }

        if (empty($defaultRoute)) {
            abort(403, 'Access Denied');
        }

        return redirect(route($defaultRoute));
    }
}
