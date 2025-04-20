<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RuanganRepository;
use App\Http\Services\RuanganServices;
use Illuminate\Http\Request;

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

        $dataRuangan = $this->service->getDataRuangan();

        return view('pages.ruangan.index', compact('title', 'dataRuangan'));
    }
}
