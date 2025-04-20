<?php

namespace App\Http\Controllers;

use App\Http\Repositories\DashboardRepository;
use App\Http\Services\DashboardServices;
use Illuminate\Http\Request;

class DashboardController extends Controller{
    private $service;
    public function __construct()
    {
        $this->service = new DashboardServices(new DashboardRepository());
    }
    public function pengguna(){
        $title = 'Dashboard Pengguna';

        return view('pages.dashboard.dashboard_pengguna', compact('title'));
    }

    public function surat(){
        $title = 'Dashboard Persuratan';

        return view('pages.dashboard.dashboard_surat', compact('title'));
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
}
