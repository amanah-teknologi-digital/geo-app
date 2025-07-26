<?php

namespace App\Http\Controllers;

use App\Http\Repositories\JenisSuratRepository;
use App\Http\Services\JenisSuratServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Nonstandard\Uuid;
use Yajra\DataTables\DataTables;

class JenisSuratController extends Controller
{
    private $service, $subtitle;
    public function __construct()
    {
        $this->service = new JenisSuratServices(new JenisSuratRepository());
        $this->subtitle = (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : 'Persuratan');
    }
    public function index()
    {
        $title = "Jenis Surat";

        return view('pages.jenis_surat.index', compact('title'));
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $dataJenisSurat = $this->service->getDataJenisSurat();

            return DataTables::of($dataJenisSurat)
                ->addIndexColumn()
                ->addColumn('jenissurat', function ($dataJenisSurat) {
                    return '<b>'.$dataJenisSurat->nama.'</b>';
                })
                ->addColumn('updater', function ($dataJenisSurat) {
                    return '<span class="text-muted" style="font-size: smaller;font-style: italic">'.
                        $dataJenisSurat->pihakupdater->name.',<br> pada '.
                        ($dataJenisSurat->updated_at ? $dataJenisSurat->updated_at->format('d-m-Y H:i') : $dataJenisSurat->created_at->format('d-m-Y H:i')).
                        '</span>';
                })
                ->addColumn('status', function ($dataJenisSurat) {
                    $html = $dataJenisSurat->is_aktif? '<span class="badge bg-sm text-success">Aktif</span>':'<span class="badge bg-sm text-warning">Tidak Aktif</span>';
                    if ($dataJenisSurat->pengajuansurat){
                        $html .= '<br><i><span class="badge bg-sm text-danger">(Dipakai)</span></i>';
                    }
                    return $html;
                })
                ->addColumn('aksi', function ($dataJenisSurat) {
                    $html = '<a href="'.route('jenissurat.edit', $dataJenisSurat->id_jenissurat).'" class="btn btn-sm py-1 px-2 btn-primary"><span class="bx bx-edit-alt"></span><span class="d-none d-lg-inline-block">&nbsp;Edit</span></a>&nbsp;';
                    $html .= '<div class="d-inline-block"><a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded icon-base"></i></a>';
                    $html .= '<div class="dropdown-menu dropdown-menu-end m-0" style="">';

                    if ($dataJenisSurat->is_aktif == 1) {
                        $html .= '<a href="javascript:;" class="dropdown-item text-warning nonaktifkan-jenissurat" data-id="'.$dataJenisSurat->id_jenissurat.'" data-bs-toggle="modal" data-bs-target="#modal-nonaktif"><span class="bx bx-no-entry"></span>&nbsp;Non Aktifkan</a>';
                    }else{
                        $html .= '<a href="javascript:;" class="dropdown-item text-success aktifkan-jenissurat" data-id="'.$dataJenisSurat->id_jenissurat.'" data-bs-toggle="modal" data-bs-target="#modal-aktif"><span class="bx bx-check"></span>&nbsp;Aktifkan</a>';
                    }

                    if (!$dataJenisSurat->pengajuansurat) {
                        $html .= '<div class="dropdown-divider"></div>';
                        $html .= '<a href="javascript:;" class="dropdown-item text-danger hapus-jenissurat" data-id="' . $dataJenisSurat->id_jenissurat . '" data-bs-toggle="modal" data-bs-target="#modal-hapus"><span class="bx bx-trash"></span>&nbsp;Hapus</a>';
                    }
                    $html .= '</div></div>';
                    return $html;
                })
                ->rawColumns(['jenissurat', 'aksi', 'updater', 'status']) // Untuk render tombol HTML
                ->filterColumn('jenissurat', function($query, $keyword) {
                    $query->where('nama', 'LIKE', "%{$keyword}%");
                })
                ->toJson();
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function tambahJenisSurat(){
        $title = "Tambah Jenis Surat";

        return view('pages.jenis_surat.tambah', compact('title'));
    }

    public function doTambahJenisSurat(Request $request){
        try {
            $request->validate([
                'nama_jenis' => ['required'],
                'editor' => ['required', 'string', 'min:10'],
                'keterangan_datadukung' => [
                    Rule::requiredIf(function () use ($request) {
                        return $request->has('is_datapendukung') && $request->is_datapendukung == '1';
                    }),
                    'string'
                ]
            ],[
                'nama_jenis.required' => 'Nama jenis surat wajib diisi.',
                'editor.required' => 'Template surat wajib diisi.',
                'keterangan_datadukung.required' => 'Keterangan data pendukung wajib diisi.',
            ]);

            DB::beginTransaction();
            //save file gambar header
            $id_jenissurat = strtoupper(Uuid::uuid4()->toString());
            $this->service->tambahJenisSurat($request, $id_jenissurat);

            DB::commit();

            return redirect(route('jenissurat'))->with('success', 'Berhasil Tambah Jenis Surat.');
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

    public function editJenisSurat($idJenisSurat){
        $title = "Edit Jenis Surat";
        $dataJenisSurat = $this->service->getDataJenisSurat($idJenisSurat);
        $pihakPenyetuju = $this->service->getPihakPenyetujuSurat($idJenisSurat);
        $namaLayananSurat = $this->subtitle;

        return view('pages.jenis_surat.edit', compact('dataJenisSurat', 'pihakPenyetuju', 'idJenisSurat', 'title', 'namaLayananSurat'));
    }

    public function getUserPenyetuju(Request $request){
        $search = $request->q;
        $idJenisSurat = $request->id_jenissurat;

        $users = $this->service->getUserPenyetujuSurat($search, $idJenisSurat);

        return response()->json($users);
    }

    public function getUserPenyetujuUpdate(Request $request){
        $search = $request->q;
        $idJenisSurat = $request->id_jenissurat;
        $idPihakPenyetuju = $request->id_pihakpenyetujusurat;

        $users = $this->service->getUserPenyetujuSuratUpdate($search, $idJenisSurat, $idPihakPenyetuju);

        return response()->json($users);
    }

    public function doEditJenisSurat(Request $request){
        try {
            $request->validate([
                'id_jenissurat' => ['required'],
                'nama_jenis' => ['required'],
                'editor' => ['required', 'string', 'min:10'],
                'keterangan_datadukung' => [
                    Rule::requiredIf(function () use ($request) {
                        return $request->has('is_datapendukung') && $request->is_datapendukung == '1';
                    }),
                    'string'
                ]
            ],[
                'id_jenissurat.required' => 'Id Jenis Surat tidak ada.',
                'nama_jenis.required' => 'Nama jenis surat wajib diisi.',
                'editor.required' => 'Template wajib diisi.',
                'keterangan_datadukung.required' => 'Keterangan data pendukung wajib diisi.',
            ]);

            DB::beginTransaction();
            //save file gambar header

            $this->service->updateJenisSurat($request);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Update Jenis Surat.');
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

    public function doTambahPenyetuju(Request $request){
        try {
            $request->validate([
                'nama_persetujuan' => ['required'],
                'id_jenissurat' => ['required'],
                'user_penyetuju' => ['required',
                    Rule::unique('pihak_penyetujusurat', 'id_penyetuju')
                        ->where(fn ($query) => $query->where('id_jenissurat', $request->id_jenissurat))
                ]
            ],[
                'nama_persetujuan.required' => 'Nama persetujuan tidak ada.',
                'id_jenissurat.required' => 'Id Jenis Surat tidak ada.',
                'user_penyetuju.required' => 'User penyetuju wajib diisi.',
            ]);

            DB::beginTransaction();

            $this->service->tambahPenyetujuSurat($request);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Tambah Penyetuju Surat.');
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

    public function doUpdatePenyetuju(Request $request){
        try {
            $request->validate([
                'id_pihakpenyetujusurat' => ['required'],
                'nama_persetujuan' => ['required'],
                'user_penyetuju' => ['required',
                    Rule::unique('pihak_penyetujusurat', 'id_penyetuju')
                        ->where(fn ($query) => $query->where('id_jenissurat', $request->id_jenissurat))->where('id_pihakpenyetuju', '!=', $request->id_pihakpenyetujusurat)
                ]
            ],[
                'id_pihakpenyetujusurat.required' => 'Id Pihak Penyetuju tidak ada.',
                'nama_persetujuan.required' => 'Nama Persetujuan wajib diisi.',
                'user_penyetuju.required' => 'User penyetuju wajib diisi.',
            ]);

            DB::beginTransaction();

            $this->service->UpdatePenyetujuSurat($request);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Update Penyetuju Surat.');
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

    public function hapusJenisSurat(Request $request){
        try {
            $request->validate([
                'id_jenissurat' => ['required'],
            ],[
                'id_jenissurat.required' => 'Id Jenis Surat tidak ada.',
            ]);

            $dataJenisSurat = $this->service->getDataJenisSurat($request->id_jenissurat);

            DB::beginTransaction();

            $this->service->hapusJenisSurat($dataJenisSurat->id_jenissurat);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Hapus Jenis Surat.');
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

    public function doHapusPenyetuju(Request $request){
        try {
            $request->validate([
                'id_pihakpenyetujusurat' => ['required'],
            ],[
                'id_pihakpenyetujusurat.required' => 'Id pihak penyetuju surat tidak ada.',
            ]);

            DB::beginTransaction();

            $this->service->hapusPihakPenyetujuSurat($request->id_pihakpenyetujusurat);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Hapus Pihak Penyetuju Surat.');
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

    public function aktifkanJenisSurat(Request $request){
        try {
            $request->validate([
                'id_jenissurat' => ['required'],
            ],[
                'id_jenissurat.required' => 'Id Jenis Surat tidak ada.',
            ]);

            $idJenisSurat = $request->id_jenissurat;

            $this->service->aktifkanJenisSurat($idJenisSurat);

            return redirect()->back()->with('success', 'Berhasil Aktifkan Jenis Surat.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function nonAktifkanJenisSurat(Request $request){
        try {
            $request->validate([
                'id_jenissurat' => ['required'],
            ],[
                'id_jenissurat.required' => 'Id Jenis Surat tidak ada.',
            ]);

            $idJenisSurat = $request->id_jenissurat;

            $this->service->nonAktifkanJenisSurat($idJenisSurat);

            return redirect()->back()->with('success', 'Berhasil Menonaktifkan Jenis Surat.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
