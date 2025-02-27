<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return redirect(route('dashboard.pengguna'));
    }
    public function pengguna(){
        return view('pages.dashboard.dashboard_pengguna');
    }
}
