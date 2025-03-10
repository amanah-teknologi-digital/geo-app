<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(){
        $pengaturan = Pengaturan::with(['files_geoletter', 'files_georoom', 'files_geofacility'])->first();
        $pengumumanterbaru = Pengumuman::with(['user','file_pengumuman','postinger_user'])
            ->where('is_posting', 1)->orderBy('created_at', 'desc')
            ->take(3)->get();

        return view('landing_page.index', compact('pengaturan','pengumumanterbaru'));
    }
}
