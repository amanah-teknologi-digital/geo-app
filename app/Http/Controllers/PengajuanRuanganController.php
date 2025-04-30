<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengajuanRuanganRepository;
use App\Http\Services\PengajuanRuanganServices;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PengajuanRuanganController extends Controller
{
    private $service;
    private $subtitle;
    public function __construct()
    {
        $this->service = new PengajuanRuanganServices(new PengajuanRuanganRepository());
        $this->subtitle = (!empty(config('variables.namaLayananSewaRuangan')) ? config('variables.namaLayananSewaRuangan') : 'Ruangan');
    }

    public function index(){
        $title = $this->subtitle;
        $isTambah = $this->service->checkAksesTambah(Auth()->user()->id_akses);

        return view('pages.pengajuan_ruangan.index', compact('isTambah','title'));
    }

    public function getData(Request $request){
        $id_akses = auth()->user()->id_akses;

        if ($request->ajax()) {
            $data_pengajuan = $this->service->getDataPengajuan();

            return DataTables::of($data_pengajuan)
                ->addIndexColumn()
                ->addColumn('jenissurat', function ($data_pengajuan) {
                    return '<b>'.$data_pengajuan->jenis_surat->nama.'</b>';
                })
                ->addColumn('pengaju', function ($data_pengajuan) {
                    return '<span class="text-muted" style="font-size: smaller;font-style: italic">'.$data_pengajuan->nama_pengaju.
                        ',<br> pada '.$data_pengajuan->created_at->format('d-m-Y H:i').'</span>';
                })
                ->addColumn('keterangan', function ($data_pengajuan) {
                    return '<span class="text-muted" style="font-size: smaller; font-style: italic">'.$data_pengajuan->keterangan.'</span>';
                })
                ->addColumn('status', function ($data_pengajuan) use($id_akses) {
                    $html = '<span style="font-size: smaller; color: '.$data_pengajuan->statuspengajuan->html_color.'">'.$data_pengajuan->statuspengajuan->nama.'</span>';
                    $html .= $this->service->getHtmlStatusPengajuan($data_pengajuan->id_statuspengajuan, $id_akses, $data_pengajuan->persetujuan);

                    return $html;
                })
                ->addColumn('aksi', function ($data_pengajuan) {
                    $html = '<a href="'.route('pengajuansurat.detail', $data_pengajuan->id_pengajuan).'" class="btn btn-sm py-1 px-2 btn-primary"><span class="bx bx-edit-alt"></span><span class="d-none d-lg-inline-block">&nbsp;Detail</span></a>';
                    if ($data_pengajuan->id_statuspengajuan == 0) { //status draft bisa hapus
                        $html .= '&nbsp;&nbsp;<a href="javascript:;" data-id="' . $data_pengajuan->id_pengajuan . '" data-bs-toggle="modal" data-bs-target="#modal-hapus" class="btn btn-sm py-1 px-2 btn-danger"><span class="bx bx-trash"></span><span class="d-none d-lg-inline-block">&nbsp;Hapus</span></a>';
                    }

                    return $html;
                })
                ->rawColumns(['jenissurat', 'aksi', 'keterangan', 'pengaju', 'status']) // Untuk render tombol HTML
                ->filterColumn('jenissurat', function($query, $keyword) {
                    $query->whereHas('jenis_surat', function ($q) use ($keyword) {
                        $q->where('jenis_surat.nama', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('keterangan', function($query, $keyword) {
                    $query->where('pengajuan_surat.keterangan', 'LIKE', "%{$keyword}%");
                })
                ->filterColumn('pengaju', function($query, $keyword) {
                    $query->where('pengajuan_surat.nama_pengaju', 'LIKE', "%{$keyword}%");
                })
                ->toJson();
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function tambahPengajuan(){
        $title = "Tambah Pengajuan";

        $dataJenisSurat = $this->service->getJenisSurat(isEdit: true);

        return view('pages.pengajuan_surat.tambah', compact('title', 'dataJenisSurat'));
    }
}
