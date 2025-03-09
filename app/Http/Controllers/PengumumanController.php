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

    public function getData(Request $request){
        if ($request->ajax()) {
            $data_pengumuman = Pengumuman::select('id_pengumuman', 'judul', 'data', 'gambar_header', 'created_at', 'updated_at', 'updater')
            ->with(['user','file_pengumuman'])->orderBy('created_at', 'DESC');

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
}
