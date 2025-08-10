<?php

namespace App\Http\Controllers;

use App\Http\Repositories\DashboardRepository;
use App\Http\Services\DashboardServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller{
    private $service;
    private $istilahPersuratan;
    private $idAkses;

    private $subtitleSurat;
    public function __construct()
    {
        $this->service = new DashboardServices(new DashboardRepository());
        $this->istilahPersuratan = (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : 'Persuratan');
        $this->idAkses = session('akses_default_id');
        $this->subtitleSurat = (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : 'Persuratan');
    }

    public function pengguna(){
        $title = 'Dashboard Pengguna';
        $istilahPersuratan = $this->istilahPersuratan;

        return view('pages.dashboard.dashboard_pengguna', compact('title' ,'istilahPersuratan'));
    }

    public function surat(){
        $title = 'Dashboard '.$this->istilahPersuratan;
        $istilahPersuratan = $this->istilahPersuratan;
        $dataSurveyKepuasan = $this->service->getSurveyKepuasan();

        return view('pages.dashboard.dashboard_surat', compact('title','istilahPersuratan', 'dataSurveyKepuasan'));
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

    public function getDataSuratPengguna(Request $request){
        $idUser = $request->get('id_user');
        $tahun = $request->tahun;
        $dataTotalPersuratan = $this->service->getDataTotalPersuratan($tahun, $idUser);
        $dataStatistikPersuratan = $this->service->getDataStatistikPersuratan($tahun, $idUser);

        $data = [
            'dataPersuratan' => $dataTotalPersuratan,
            'dataStatistikPersuratan' => $dataStatistikPersuratan
        ];
        return response()->json($data);
    }

    public function getDataNotifikasi(){
        $idAkses = $this->idAkses;
        $dataNotifSurat = $this->service->getDataNotifSurat($idAkses, $this->subtitleSurat);

        $data = [
            'dataNotifSurat' => $dataNotifSurat
        ];

        return response()->json($data);
    }

    public function gantiHakAkses(Request $request){
        $id_akses = $request->id_akses;
        $user = Auth::user();
        // Load akses secara aman
        $aksesList = $user->aksesuser;
        $defaultAkses = $aksesList->firstWhere('id_akses', $id_akses);

        if (!$defaultAkses) {
            return redirect()->back()->with('error', 'Anda tidak punya otoritas atas akses ini!.');
        }

        $dataHalaman = $defaultAkses->akses->akseshalaman;
        $defaultRoute = $defaultAkses->akses->halaman->url;

        session([
            'akses_default_id' => $defaultAkses->id_akses,
            'akses_default_nama' => $defaultAkses->akses->nama,
            'akses_default_halaman' => $dataHalaman,
            'akses_default_halaman_route' => $defaultAkses->akses->halaman->url
        ]);

        return redirect(route($defaultRoute))->with('success', 'Berhasil ganti hak akses.');
    }
}
