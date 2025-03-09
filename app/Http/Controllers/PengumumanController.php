<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengumumanRepository;
use App\Http\Services\PengumumanServices;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PengumumanController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new PengumumanServices(new PengumumanRepository());
    }
    public function index()
    {
        $title = "Pengumuman";

        return view('pages.pengumuman.index', compact('title'));
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data_pengumuman = $this->service->getDataPengumuman();

            return DataTables::of($data_pengumuman)
                ->addIndexColumn()
                ->addColumn('judul', function ($data_pengumuman) {
                    return $data_pengumuman->judul;
                })
                ->addColumn('author', function ($data_pengumuman) {
                    return $data_pengumuman->user->name;
                })
                ->editColumn('tanggal_post', function ($data_pengumuman) {
                    return $data_pengumuman->created_at->format('d-m-Y');
                })
                ->addColumn('is_posting', function ($data_pengumuman) {
                    return $data_pengumuman->is_posting? '<span class="badge bg-success">posting</span>':'<span class="badge bg-danger">tidak</span>';
                })
                ->addColumn('action', function ($data_pengumuman) {
                    return '
                        <a href="'.url('users/edit/'.$data_pengumuman->id_pengumuman).'" class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger delete-user" data-id="'.$data_pengumuman->id_pengumuman.'">Delete</button>
                    ';
                })
                ->rawColumns(['action']) // Untuk render tombol HTML
                ->toJson();
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function tambahPengumuman(){
        $title = "Tambah Pengumuman";

        return view('pages.pengumuman.tambah', compact('title'));
    }

    public function dotambahPengumuman(Request $request){
        dd($request);
    }
}
