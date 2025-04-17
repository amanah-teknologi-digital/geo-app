<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengajuanPersuratanRepository;
use App\Http\Services\PengajuanPersuratanServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Nonstandard\Uuid;
use Yajra\DataTables\DataTables;

class PengajuanPersuratanController extends Controller
{
    private $service;
    private $subtitle;
    public function __construct()
    {
        $this->service = new PengajuanPersuratanServices(new PengajuanPersuratanRepository());
        $this->subtitle = (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : '');
    }

    public function index(){
        $title = $this->subtitle;

        return view('pages.pengajuan_surat.index', compact('title'));
    }

    public function getData(Request $request){
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
                ->addColumn('status', function ($data_pengajuan) {
                    return '<span style="font-size: smaller; color: '.$data_pengajuan->statuspengajuan->html_color.'">'.$data_pengajuan->statuspengajuan->nama.'</span>';
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

    public function getJenisSurat(Request $request){
        $id_jenissurat = $request->id_jenissurat;
        $data = $this->service->getJenisSurat($id_jenissurat);

        return response()->json($data);
    }

    public function tambahPengajuan(){
        $title = "Tambah Pengajuan";

        $dataJenisSurat = $this->service->getJenisSurat();

        return view('pages.pengajuan_surat.tambah', compact('title', 'dataJenisSurat'));
    }

    public function doTambahPengajuan(Request $request){
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

            return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('success', 'Berhasil Tambah Pengajuan.');
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

    public function hapusPengajuan(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required'],
            ],[
                'id_pengajuan.required' => 'Id Pengajuan tidak ada.',
            ]);

            $dataPengajuan = $this->service->getDataPengajuan($request->id_pengajuan);

            DB::beginTransaction();

            $this->service->hapusPengajuan($dataPengajuan->id_pengajuan);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Hapus Pengajuan.');
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
        $title = "Detail Pengajuan";

        $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);
        $dataJenisSurat = $this->service->getJenisSurat();
        $isEdit = $this->service->checkOtoritasPengajuan($dataPengajuan->id_statuspengajuan);
        //$isEdit = false;
        $statusVerifikasi = $this->service->getStatusVerifikasi($id_pengajuan);

        return view('pages.pengajuan_surat.detail', compact('dataPengajuan', 'dataJenisSurat', 'id_pengajuan', 'isEdit', 'statusVerifikasi', 'title'));
    }

    public function doUpdatePengajuan(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required'],
                'jenis_surat' => ['required'],
                'editor_quil' => ['required'],
                'keterangan' => ['required']
            ],[
                'id_pengajuan.required' => 'Id Pengajuan wajib diisi.',
                'jenis_surat.required' => 'Jenis Surat wajib diisi.',
                'editor_quil.required' => 'Konten wajib diisi.',
                'keterangan.required' => 'Keterangan wajib diisi.'
            ]);

            DB::beginTransaction();
            //save file gambar header
            $id_pengajuan = $request->id_pengajuan;
            $this->service->updatePengajuan($request);

            DB::commit();

            return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('success', 'Berhasil Update Pengajuan.');
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

    public function ajukanPengajuan(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required']
            ],[
                'id_pengajuan.required' => 'Id Pengajuan wajib diisi.'
            ]);

            $id_pengajuan = $request->id_pengajuan;
            $id_akses = $request->id_akses;
            if (empty($id_akses)){
                $id_akses = auth()->user()->id_akses;
            }

            $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);

            DB::beginTransaction();

            if ($dataPengajuan->id_statuspengajuan == 0) {
                $this->service->ajukanPengajuan($id_pengajuan); //ubah status pengajuan
                $this->service->tambahPersetujuan($id_pengajuan, $id_akses, 2);
            }

            DB::commit();

            return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('success', 'Berhasil Mengajukan Pengajuan.');
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

    public function setujuiPengajuan(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required']
            ],[
                'id_pengajuan.required' => 'Id Pengajuan wajib diisi.'
            ]);

            $id_pengajuan = $request->id_pengajuan;
            $id_akses = $request->id_akses;
            if (empty($id_akses)){
                $id_akses = auth()->user()->id_akses;
            }

            $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);

            DB::beginTransaction();

            if ($dataPengajuan->id_statuspengajuan == 2 || $dataPengajuan->id_statuspengajuan == 5) {
                if ($id_akses == 2) { //jika admin
                    $this->service->setujuiPengajuan($id_pengajuan); //ubah status pengajuan
                }
                $this->service->tambahPersetujuan($id_pengajuan, $id_akses, 1);
            }

            DB::commit();

            return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('success', 'Berhasil Setujui Pengajuan.');
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
}
