<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(){
        $pengaturan = Pengaturan::with(['files_geoletter', 'files_georoom', 'files_geofacility'])->first();

        return view('landing_page.index', compact('pengaturan'));
    }
}
