<?php

namespace App\Http\Controllers;

use App\Http\Repositories\DashboardRepository;
use App\Http\Services\DashboardServices;
use Illuminate\Http\Request;

class DashboardController extends Controller{
    private $service;
    private $istilahPersuratan;
    public function __construct()
    {
        $this->service = new DashboardServices(new DashboardRepository());
        $this->istilahPersuratan = (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : 'Persuratan');
    }
    public function pengguna(){
        $title = 'Dashboard Pengguna';
        $istilahPersuratan = $this->istilahPersuratan;

        return view('pages.dashboard.dashboard_pengguna', compact('title' ,'istilahPersuratan'));
    }

    public function surat(){
        $title = 'Dashboard '.$this->istilahPersuratan;
        $istilahPersuratan = $this->istilahPersuratan;

        return view('pages.dashboard.dashboard_surat', compact('title','istilahPersuratan'));
    }

    public function ruangan(){
        $title = 'Dashboard Ruangan';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }

    public function peralatan(){
        $title = 'Dashboard Peralatan';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }

    public function getDataSurat(Request $request){
        $tahun = $request->tahun;
        $dataTotalPersuratan = $this->service->getDataTotalPersuratan($tahun);
        $dataStatistikPersuratan = $this->service->getDataStatistikPersuratan($tahun);

        $data = [
            'dataPersuratan' => $dataTotalPersuratan,
            'dataStatistikPersuratan' => $dataStatistikPersuratan
        ];
        return response()->json($data);
    }

    public function getDataNotifikasi(){
        $idAkses = auth()->user()->id_akses;
        $dataNotifSurat = $this->service->getDataNotifSurat($idAkses);

        $data = [
            'dataNotifSurat' => $dataNotifSurat
        ];

        return response()->json($data);
    }
}
