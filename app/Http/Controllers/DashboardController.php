<?php

namespace App\Http\Controllers;

use App\Http\Repositories\DashboardRepository;
use App\Http\Services\DashboardServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller{
    private $service;
    private $idAkses;

    private $subtitleSurat;
    private $subtitleRuangan;

    private $subtitlePeralatan;
    public function __construct()
    {
        $this->service = new DashboardServices(new DashboardRepository());
        $this->idAkses = session('akses_default_id');
        $this->subtitleSurat = (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : 'Persuratan');
        $this->subtitleRuangan = (!empty(config('variables.namaLayananSewaRuangan')) ? config('variables.namaLayananSewaRuangan') : 'Ruangan');
        $this->subtitlePeralatan = (!empty(config('variables.namaLayananSewaPeralatan')) ? config('variables.namaLayananSewaPeralatan') : 'Peralatan');
    }

    public function pengguna(){
        $title = 'Dashboard Pengguna';
        $istilahPersuratan = $this->subtitleRuangan;

        return view('pages.dashboard.dashboard_pengguna', compact('title' ,'istilahPersuratan'));
    }

    public function surat(){
        $title = 'Dashboard '.$this->subtitleSurat;
        $istilahPersuratan = $this->subtitleSurat;
        $dataSurveyKepuasan = $this->service->getSurveyKepuasan();

        return view('pages.dashboard.dashboard_surat', compact('title','istilahPersuratan', 'dataSurveyKepuasan'));
    }

    public function ruangan(){
        $title = 'Dashboard '.$this->subtitleRuangan;
        $istilahRuangan = $this->subtitleRuangan;
        $dataSurveyKepuasan = $this->service->getSurveyKepuasanRuang();

        return view('pages.dashboard.dashboard_ruangan', compact('title','istilahRuangan', 'dataSurveyKepuasan'));
    }

    public function peralatan(){
        $title = 'Dashboard Peralatan';
        $istilahPersuratan = $this->subtitleRuangan;

        return view('pages.dashboard.dashboard_pengguna', compact('title', 'istilahPersuratan'));
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

    public function getDataRuang(Request $request){
        $tahun = $request->tahun;
        $dataTotalRuangan = $this->service->getDataTotalPengajuanRuangan($tahun);
        $dataStatistikRuangan = $this->service->getDataStatistikRuangan($tahun);

        $data = [
            'dataRuangan' => $dataTotalRuangan,
            'dataStatistikRuangan' => $dataStatistikRuangan
        ];
        return response()->json($data);
    }

    public function getDataSuratPengguna(Request $request){
        $idUser = $request->get('id_user');
        $tahun = $request->tahun;
        $dataTotal = $this->service->getDataTotal($tahun, $idUser);
        $dataStatistik = $this->service->getDataStatistik($tahun, $idUser);

        $data = [
            'data' => $dataTotal,
            'dataStatistik' => $dataStatistik
        ];
        return response()->json($data);
    }

    public function getDataNotifikasi(){
        $idAkses = $this->idAkses;
        $dataNotifSurat = $this->service->getDataNotifSurat($idAkses, $this->subtitleSurat);
        $dataNotifRuangan = $this->service->getDataNotifRuangan($idAkses, $this->subtitleRuangan);

        $data = [
            'dataNotifSurat' => $dataNotifSurat,
            'dataNotifRuangan' => $dataNotifRuangan
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
