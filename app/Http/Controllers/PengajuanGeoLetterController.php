<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengajuanGeoLetterRepository;
use App\Http\Services\PengajuanGeoLetterServices;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PengajuanGeoLetterController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new PengajuanGeoLetterServices(new PengajuanGeoLetterRepository());
    }

    public function index(){
        $title = "List Pengajuan GeoLetter";

        return view('pages.pengajuan_geoletter.index', compact('title'));
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data_pengumuman = $this->service->getDataPengumuman();

            return DataTables::of($data_pengumuman)
                ->addIndexColumn()
                ->addColumn('judul', function ($data_pengumuman) {
                    return $data_pengumuman->judul;
                })
                ->addColumn('pembuat', function ($data_pengumuman) {
                    return '<span class="text-muted" style="font-size: smaller;font-style: italic">'.$data_pengumuman->user->name.
                        ',<br> pada '.$data_pengumuman->created_at->format('d-m-Y H:i').'</span>';
                })
                ->addColumn('posting', function ($data_pengumuman) {
                    return $data_pengumuman->is_posting? '<span class="badge bg-sm text-success">Posting</span>':'<span class="badge bg-sm text-warning">Tidak</span>';
                })
                ->addColumn('aksi', function ($data_pengumuman) {
                    $html = '<a href="'.route('pengumuman.edit', $data_pengumuman->id_pengumuman).'" class="btn btn-sm py-1 px-2 btn-primary"><span class="bx bx-edit-alt"></span><span class="d-none d-lg-inline-block">&nbsp;Edit</span></a>&nbsp;';
                    $html .= '<div class="d-inline-block"><a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded icon-base"></i></a>';
                    $html .= '<div class="dropdown-menu dropdown-menu-end m-0" style="">';
                    if ($data_pengumuman->is_posting == 1) {
                        $html .= '<a href="javascript:;" class="dropdown-item text-warning batal-posting" data-id="'.$data_pengumuman->id_pengumuman.'" data-bs-toggle="modal" data-bs-target="#modal-unpost"><span class="bx bx-candles"></span>&nbsp;Unposting</a>';
                    }else{
                        $html .= '<a href="javascript:;" class="dropdown-item text-success posting-pengumuman" data-id="'.$data_pengumuman->id_pengumuman.'" data-bs-toggle="modal" data-bs-target="#modal-post"><span class="bx bx-paper-plane"></span>&nbsp;Posting</a>';
                    }
                    $html .= '<div class="dropdown-divider"></div>';
                    $html .= '<a href="javascript:;" class="dropdown-item text-danger delete-record" data-id="'.$data_pengumuman->id_pengumuman.'" data-bs-toggle="modal" data-bs-target="#modal-hapus"><span class="bx bx-trash"></span>&nbsp;Hapus</a>';
                    $html .= '</div></div>';
                    return $html;
                })
                ->rawColumns(['aksi', 'posting', 'pembuat']) // Untuk render tombol HTML
                ->filterColumn('judul', function($query, $keyword) {
                    $query->where('judul', 'LIKE', "%{$keyword}%");
                })
                ->filterColumn('created_at', function($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i') LIKE ?", ["%{$keyword}%"]);
                })
                ->toJson();
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }
}
