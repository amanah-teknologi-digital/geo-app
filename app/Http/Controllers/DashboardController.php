<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller{
    public function pengguna(){
        $title = 'Dashboard Pengguna';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }
}
