<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengajuanGeoLetterRepository;
use App\Http\Services\PengajuanGeoLetterServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Nonstandard\Uuid;
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
            $data_pengajuan = $this->service->getDataPengajuan();

            return DataTables::of($data_pengajuan)
                ->addIndexColumn()
                ->addColumn('jenissurat', function ($data_pengajuan) {
                    return $data_pengajuan->jenissurat->nama;
                })
                ->addColumn('pengaju', function ($data_pengajuan) {
                    return '<span class="text-muted" style="font-size: smaller;font-style: italic">'.$data_pengajuan->pihakpengaju->name.
                        ',<br> pada '.$data_pengajuan->created_at->format('d-m-Y H:i').'</span>';
                })
                ->addColumn('keterangan', function ($data_pengajuan) {
                    return '<span class="text-muted" style="font-size: smaller; font-style: italic">'.$data_pengajuan->keterangan.'</span>';
                })
                ->addColumn('aksi', function ($data_pengajuan) {
                    $html = '<a href="'.route('pengajuangeoletter.detail', $data_pengajuan->id_pengajuan).'" class="btn btn-sm py-1 px-2 btn-primary"><span class="bx bx-edit-alt"></span><span class="d-none d-lg-inline-block">&nbsp;Detail</span></a>&nbsp;';
                    $html .= '<div class="d-inline-block"><a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded icon-base"></i></a>';
                    $html .= '<div class="dropdown-menu dropdown-menu-end m-0" style="">';
                    $html .= '<div class="dropdown-divider"></div>';
                    $html .= '<a href="javascript:;" class="dropdown-item text-danger delete-record" data-id="'.$data_pengajuan->id_pengajuan.'" data-bs-toggle="modal" data-bs-target="#modal-hapus"><span class="bx bx-trash"></span>&nbsp;Hapus</a>';
                    $html .= '</div></div>';
                    return $html;
                })
                ->rawColumns(['aksi', 'keterangan', 'pengaju']) // Untuk render tombol HTML
                ->filterColumn('jenissurat', function($query, $keyword) {
                    $query->whereHas('jenissurat', function ($q) use ($keyword) {
                        $q->where('nama', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('keterangan', function($query, $keyword) {
                    $query->where('keterangan', 'LIKE', "%{$keyword}%");
                })
                ->toJson();
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function getJenisSurat(Request $request){
        $id_jenissurat = $request->id_jenissurat;
        $data = $this->service->getJenisSurat($id_jenissurat);

        return response()->json($data);
    }

    public function tambahPengajuan(){
        $title = "Tambah Pengajuan";

        $data_jenissurat = $this->service->getJenisSurat();

        return view('pages.pengajuan_geoletter.tambah', compact('title','data_jenissurat'));
    }

    public function dotambahPengajuan(Request $request){
        try {
            $request->validate([
                'jenis_surat' => ['required'],
                'editor_quil' => ['required'],
                'keterangan' => ['required']
            ],[
                'jenis_surat.required' => 'Jenis Surat wajib diisi.',
                'editor_quil.required' => 'Konten wajib diisi.',
                'keterangan.required' => 'Keterangan wajib diisi.'
            ]);

            DB::beginTransaction();
            //save file gambar header
            $id_pengajuan = strtoupper(Uuid::uuid4()->toString());
            $this->service->tambahPengajuan($request, $id_pengajuan);

            DB::commit();

            return redirect(route('pengajuangeoletter.detail', $id_pengajuan))->with('success', 'Berhasil Tambah Pengajuan Geo Letter.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function detailPengajuan($id_pengajuan){
        $title = "Detail Pengajuan Geo Letter";
        $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);
        if ($dataPengajuan->id_statuspengajuan == 0) {
            $is_edit = true;
        }else{
            $is_edit = false;
        }

        return view('pages.pengajuan_geoletter.detail', compact('dataPengajuan', 'is_edit', 'title'));
    }
}
