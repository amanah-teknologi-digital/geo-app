<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller{
    public function pengguna(){
        $title = 'Dashboard Pengguna';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }

    public function letter(){
        $title = 'Dashboard Persuratan';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }

    public function room(){
        $title = 'Dashboard Ruangan';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }

    public function facility(){
        $title = 'Dashboard Peralatan';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }
}
