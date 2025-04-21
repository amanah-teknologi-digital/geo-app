<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RuanganRepository;
use App\Http\Services\RuanganServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RuanganController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new RuanganServices(new RuanganRepository());
    }
    public function index()
    {
        $title = "Ruangan";
        $isTambah = $this->service->checkAksesTambah(Auth()->user()->id_akses);
        $dataRuangan = $this->service->getDataRuangan();

        return view('pages.ruangan.index', compact('title', 'dataRuangan','isTambah'));
    }

    public function tambahRuangan(){
        $title = "Tambah Ruangan";

        return view('pages.ruangan.tambah', compact('title'));
    }

    public function detailRuangan($idRuangan){
        $title = "Detail Ruangan";
        $isEdit = $this->service->checkAksesEdit(Auth()->user()->id_akses);
        $dataRuangan = $this->service->getDataRuangan($idRuangan);

        if ($isEdit){
            return view('pages.ruangan.edit', compact('title','dataRuangan'));
        }else{
            return view('pages.ruangan.detail', compact('title','dataRuangan'));
        }
    }
}
