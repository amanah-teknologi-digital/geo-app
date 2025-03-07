<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengumumanRepository;
use App\Http\Services\PengumumanServices;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    private $service;
    private $title;
    public function __construct()
    {
        $this->service = new PengumumanServices(new PengumumanRepository());
        $this->title = 'Pengumuman';
    }
    public function index()
    {
        $title = $this->title;
        $dataPengaturan = $this->service->getDataPengaturan();

        return view('pages.pengumuman.index', compact('dataPengaturan','title'));
    }
}
