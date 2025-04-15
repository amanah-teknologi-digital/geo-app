<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller{
    public function pengguna(){
        $title = 'Dashboard Pengguna';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }

    public function surat(){
        $title = 'Dashboard Persuratan';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }

    public function ruangan(){
        $title = 'Dashboard Ruangan';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }

    public function peralatan(){
        $title = 'Dashboard Peralatan';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }
}
