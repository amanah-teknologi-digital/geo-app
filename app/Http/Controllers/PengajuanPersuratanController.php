<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengajuanPersuratanRepository;
use App\Http\Services\PengajuanPersuratanServices;
use App\Models\PengajuanPersuratan;
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
    private $idAkses;

    public function __construct()
    {
        $this->service = new PengajuanPersuratanServices(new PengajuanPersuratanRepository());
        $this->subtitle = (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : 'Persuratan');
        $this->idAkses = session('akses_default_id');
    }

    public function index(){
        $title = $this->subtitle;
        $isTambah = $this->service->checkAksesTambah($this->idAkses);

        return view('pages.pengajuan_surat.index', compact('isTambah','title'));
    }

    public function getData(Request $request){
        $id_akses = $this->idAkses;
        $namaLayananSurat = $this->subtitle;

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
                ->addColumn('status', function ($data_pengajuan) use($namaLayananSurat) {
                    $html = '<span style="font-size: smaller; color: '.$data_pengajuan->statuspengajuan->html_color.'">'.$data_pengajuan->statuspengajuan->nama.'</span>';
                    $html .= $this->service->getHtmlStatusPengajuan($data_pengajuan->id_pengajuan, $namaLayananSurat, $data_pengajuan);

                    if ($data_pengajuan->id_statuspengajuan == 1){
                        if (!empty($data_pengajuan->surveykepuasan)){
                            $tmp_stars = '';
                            for ($i = 1; $i <= 5; $i++){
                                if ($i <= $data_pengajuan->surveykepuasan->rating){
                                    $color = '#f5b301';
                                }else{
                                    $color = '#ddd';
                                }
                                $tmp_stars .= '<span style="color: '.$color.'">★</span>';
                            }
                        }else{
                            $tmp_stars = '<span class="text-danger fst-italic small">Belum mengisi survey</span>';
                        }
                        $html .= '<br>'.$tmp_stars;
                    }

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

    public function getJenisSurat(Request $request){
        $id_jenissurat = $request->id_jenissurat;
        $data = $this->service->getJenisSurat($id_jenissurat);

        return response()->json($data);
    }

    public function tambahPengajuan(){
        $title = "Tambah Pengajuan";

        $dataJenisSurat = $this->service->getJenisSurat(isEdit: true);
        $namaLayananSurat = $this->subtitle;

        return view('pages.pengajuan_surat.tambah', compact('title', 'dataJenisSurat','namaLayananSurat'));
    }

    public function doTambahPengajuan(Request $request){
        try {
            $request->validate([
                'jenis_surat' => ['required'],
                'editor_surat' => ['required', 'string', 'min:10'],
                'keterangan' => ['required'],
                'data_pendukung' => [function ($attribute, $value, $fail) use ($request) {
                    $dataSurat = $this->service->getJenisSurat($request->jenis_surat, true);
                    if ($dataSurat->is_datapendukung == 1 && !$request->hasFile('data_pendukung')) {
                        $fail('Data Pendukung wajib diisi.');
                    }
                }]
            ],[
                'jenis_surat.required' => 'Jenis Surat wajib diisi.',
                'editor_surat.required' => 'Surat wajib diisi.',
                'keterangan.required' => 'Keterangan wajib diisi.'
            ]);

            $dataSurat = $this->service->getJenisSurat($request->jenis_surat);

            DB::beginTransaction();
            //save
            $id_pengajuan = strtoupper(Uuid::uuid4()->toString());
            $this->service->tambahPengajuan($request, $id_pengajuan);
            if ($request->hasFile('data_pendukung')) {
                $idFile = strtoupper(Uuid::uuid4()->toString());
                $file = $request->file('data_pendukung');
                $this->service->tambahFile($file, $idFile);
                $this->service->updateFileSurat($id_pengajuan, $dataSurat->nama_datapendukung, $idFile);
            }

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

    public function hapusFile(Request $request){
        try {
            $request->validate([
                'id_file' => ['required'],
                'id_pengajuan' => ['required'],
            ],[
                'id_file.required' => 'Id Pengajuan tidak ada.',
                'id_pengajuan.required' => 'Id File tidak ada.',
            ]);

            $userTambahan = $this->getUserTambahan($request->id_pengajuan);
            if ((in_array($this->idAkses, [1,2]) OR $userTambahan)) {
                $dataFile = $this->service->getDataFile($request->id_file);
                $location = $dataFile->location;

                DB::beginTransaction();

                $this->service->hapusFile($request->id_pengajuan, $request->id_file, $location);

                DB::commit();

                return redirect()->back()->with('success', 'Berhasil Hapus File.');
            }else{
                return redirect()->back()->with('error', 'Tidak ada akses untuk menghapus!.');
            }
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

    public function hapusFilePendukung(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required'],
            ],[
                'id_pengajuan.required' => 'Id File tidak ada.',
            ]);

            $dataPengajuan = $this->service->getDataPengajuan($request->id_pengajuan);
            $isEdit = $this->service->checkOtoritasPengajuan($dataPengajuan->id_statuspengajuan);

            if ($isEdit) {
                $dataFile = $this->service->getDataFile($dataPengajuan->id_datapendukung);
                $location = $dataFile->location;

                DB::beginTransaction();

                $this->service->hapusFilePendukung($request->id_pengajuan, $request->id_file, $location);

                DB::commit();

                return redirect()->back()->with('success', 'Berhasil Hapus File Pengajuan.');
            }else{
                return redirect()->back()->with('error', 'Tidak ada akses untuk menghapus!.');
            }
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

    public function uploadFile(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required'],
                'filesuratupload.*' => ['required', 'file', 'mimes:pdf', 'max:5048'],
            ],[
                'id_pengajuan.required' => 'Id Pengajuan wajib diisi.',
                'filesuratupload.*.required' => 'File kosong.',
                'filesuratupload.*.file' => 'File yang diunggah tidak valid.',
                'filesuratupload.*.max' => 'Ukuran akumulasi file tidak boleh lebih dari 5 MB.',
            ]);

            $id_pengajuan = $request->id_pengajuan;
            $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);
            $userTambahan = $this->getUserTambahan($id_pengajuan);

            if ((in_array($this->idAkses, [1,2]) OR $userTambahan) && $dataPengajuan->id_statuspengajuan == 1) {
                DB::beginTransaction();

                $listFile = $request->file('filesuratupload');
                foreach ($listFile as $file){
                    $idFile = strtoupper(Uuid::uuid4()->toString());
                    $this->service->tambahFile($file, $idFile);
                    $this->service->tambahFileSurat($id_pengajuan, $idFile);
                }

                DB::commit();

                return redirect()->back()->with('success', 'Berhasil Upload File.');
            }else{
                return redirect()->back()->with('error', 'Tidak ada akses untuk mengupload!.');
            }
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

    public function getUserTambahan($idPengajuan){
        $getPihakPenyetuju = $this->service->getPihakPenyetujuByIdpengajuan($idPengajuan);
        $idUser = auth()->user()->id;
        $userTambahan = false;

        if (!empty($getPihakPenyetuju)){
            if ($getPihakPenyetuju->id_penyetuju == $idUser){
                $userTambahan = true;
            }
        }

        return $userTambahan;
    }

    public function detailPengajuan($id_pengajuan){
        $title = "Detail Pengajuan";

        $namaLayananSurat = $this->subtitle;
        $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);
        $isEdit = $this->service->checkOtoritasPengajuan($dataPengajuan->id_statuspengajuan);
        $dataJenisSurat = $this->service->getJenisSurat(isEdit: $isEdit);
        $idAkses = $this->idAkses;
        $userTambahan = $this->getUserTambahan($id_pengajuan);

        //$isEdit = false;
        if ($isEdit){
            //update data pemohon pengajuan
            try {
                DB::beginTransaction();

                $this->service->updateDataPemohon($id_pengajuan);

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        $statusVerifikasi = $this->service->getStatusVerifikasi($id_pengajuan, $this->subtitle);

        return view('pages.pengajuan_surat.detail', compact('dataPengajuan', 'dataJenisSurat', 'id_pengajuan', 'isEdit', 'statusVerifikasi', 'title', 'idAkses', 'namaLayananSurat', 'userTambahan'));
    }

    public function doUpdatePengajuan(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required'],
                'jenis_surat' => ['required'],
                'editor_surat' => ['required', 'string', 'min:10'],
                'keterangan' => ['required'],
                'data_pendukung' => [function($attribute, $value, $fail) use ($request) {
                    if ($request->has('id_pengajuan')) {
                        // cek di DB apakah nama_datapendukung tidak kosong
                        $pengajuan = PengajuanPersuratan::where('id_pengajuan', $request->id_pengajuan)->first();

                        if ($pengajuan && !empty($pengajuan->nama_pendukung)) {
                            // file harus ada kalau nama_datapendukung tidak kosong
                            if (!$request->hasFile('data_pendukung')) {
                                $fail('File Data Pendukung wajib diupload karena sudah ada data pendukung sebelumnya.');
                            }
                        }
                    }
                }]
            ],[
                'id_pengajuan.required' => 'Id Pengajuan wajib diisi.',
                'jenis_surat.required' => 'Jenis Surat wajib diisi.',
                'editor_surat.required' => 'Surat wajib diisi.',
                'keterangan.required' => 'Keterangan wajib diisi.'
            ]);

            DB::beginTransaction();
            //save file gambar header
            $id_pengajuan = $request->id_pengajuan;
            $this->service->updatePengajuan($request);
            if ($request->hasFile('data_pendukung')) {
                $idFile = strtoupper(Uuid::uuid4()->toString());
                $file = $request->file('data_pendukung');
                $this->service->tambahFile($file, $idFile);
                $this->service->updateFileSuratUpdate($id_pengajuan, $idFile);
            }

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
                $id_akses = $this->idAkses;
            }

            $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);

            if (!empty($dataPengajuan->nama_pendukung) && empty($dataPengajuan->id_datapendukung)){
                return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('error', 'Data Pendukung belum diisi.');
            }

            DB::beginTransaction();

            if ($dataPengajuan->id_statuspengajuan == 0) {
                $this->service->ajukanPengajuan($id_pengajuan); //ubah status pengajuan
                $this->service->tambahPersetujuan($id_pengajuan, $id_akses, 2, null);
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
                'id_pengajuan' => ['required'],
                'filesurat.*' => ['file', 'mimes:pdf', 'max:5048'],
            ],[
                'id_pengajuan.required' => 'Id Pengajuan wajib diisi.',
                'filesurat.*.file' => 'File yang diunggah tidak valid.',
                'filesurat.*.max' => 'Ukuran akumulasi file tidak boleh lebih dari 5 MB.',
            ]);

            $id_pengajuan = $request->id_pengajuan;
            $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);
            if (!empty($dataPengajuan->nama_pendukung) && empty($dataPengajuan->id_datapendukung)){
                return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('error', 'Data Pendukung belum diisi.');
            }

            $id_pengajuan = $request->id_pengajuan;
            $id_akses = $request->id_akses;
            $idPihakpenyetuju = $request->id_pihakpenyetuju;
            $getPihakPenyetuju = $this->service->getPihakPenyetujuByIdpengajuan($id_pengajuan);
            $userTambahan = false;

            if (empty($idPihakpenyetuju)) {
                if (empty($id_akses)) {
                    $id_akses = $this->idAkses;
                }
            }else{
                if ($getPihakPenyetuju->id_pihakpenyetuju == $idPihakpenyetuju) {
                    $userTambahan = true;
                    $id_akses = null;
                }else {
                    return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('error', 'Pihak penyetuju tidak sama.');
                }
            }

            $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);

            DB::beginTransaction();

            if ($dataPengajuan->id_statuspengajuan == 2 || $dataPengajuan->id_statuspengajuan == 5) {
                if (($id_akses == 2 && empty($getPihakPenyetuju)) OR $userTambahan) { //jika admin
                    $this->service->setujuiPengajuan($id_pengajuan); //ubah status pengajuan
                    if ($request->hasFile('filesurat')) {
                        $listFile = $request->file('filesurat');
                        foreach ($listFile as $file){
                            $idFile = strtoupper(Uuid::uuid4()->toString());
                            $this->service->tambahFile($file, $idFile);
                            $this->service->tambahFileSurat($id_pengajuan, $idFile);
                        }
                    }
                }else{
                    $this->service->ajukanPengajuan($id_pengajuan); //ubah ke status diajukan
                }
                $this->service->tambahPersetujuan($id_pengajuan, $id_akses, 1, $idPihakpenyetuju);
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

    public function revisiPengajuan(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required'],
                'keteranganrev' => ['required']
            ],[
                'id_pengajuan.required' => 'Id Pengajuan wajib diisi.',
                'keteranganrev.required' => 'Keterangan revisi wajib diisi.'
            ]);

            $id_pengajuan = $request->id_pengajuan;
            $id_akses = $request->id_akses;
            $idPihakpenyetuju = $request->id_pihakpenyetuju;
            $getPihakPenyetuju = $this->service->getPihakPenyetujuByIdpengajuan($id_pengajuan);

            if (empty($idPihakpenyetuju)) {
                if (empty($id_akses)) {
                    $id_akses = $this->idAkses;
                }
            }else{
                if ($getPihakPenyetuju->id_pihakpenyetuju == $idPihakpenyetuju){
                    $id_akses = null;
                }else{
                    return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('error', 'Pihak penyetuju tidak sama.');
                }
            }

            $keterangan = $request->keteranganrev;

            $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);

            DB::beginTransaction();

            if ($dataPengajuan->id_statuspengajuan == 2 || $dataPengajuan->id_statuspengajuan == 5) {
                $this->service->revisiPengajuan($id_pengajuan, $idPihakpenyetuju); //ubah status pengajuan
                $this->service->tambahPersetujuan($id_pengajuan, $id_akses, 4, $idPihakpenyetuju, $keterangan);
            }

            DB::commit();

            return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('success', 'Berhasil Revisi Pengajuan.');
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

    public function sudahRevisiPengajuan(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required'],
                'keterangansudahrev' => ['required']
            ],[
                'id_pengajuan.required' => 'Id Pengajuan wajib diisi.',
                'keterangansudahrev.required' => 'Keterangan sudah revisi wajib diisi.'
            ]);

            $id_pengajuan = $request->id_pengajuan;
            $id_akses = $request->id_akses;
            if (empty($id_akses)){
                $id_akses = $this->idAkses;
            }
            $keterangan = $request->keterangansudahrev;

            $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);

            if (!empty($dataPengajuan->nama_pendukung) && empty($dataPengajuan->id_datapendukung)){
                return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('error', 'Data Pendukung belum diisi.');
            }

            DB::beginTransaction();

            if ($dataPengajuan->id_statuspengajuan == 4) {
                $this->service->sudahRevisiPengajuan($id_pengajuan); //ubah status pengajuan
                $this->service->tambahPersetujuan($id_pengajuan, $id_akses, 5, null, $keterangan);
            }

            DB::commit();

            return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('success', 'Berhasil Ajukan Revisi Pengajuan.');
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

    public function tolakPengajuan(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required'],
                'keterangantolak' => ['required']
            ],[
                'id_pengajuan.required' => 'Id Pengajuan wajib diisi.',
                'keterangantolak.required' => 'Keterangan tolak wajib diisi.'
            ]);

            $id_pengajuan = $request->id_pengajuan;
            $id_akses = $request->id_akses;
            $idPihakpenyetuju = $request->id_pihakpenyetuju;
            $getPihakPenyetuju = $this->service->getPihakPenyetujuByIdpengajuan($id_pengajuan);

            if (empty($idPihakpenyetuju)) {
                if (empty($id_akses)) {
                    $id_akses = $this->idAkses;
                }
            }else{
                if ($getPihakPenyetuju->id_pihakpenyetuju == $idPihakpenyetuju){
                    $id_akses = null;
                }else{
                    return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('error', 'Pihak penyetuju tidak sama.');
                }
            }
            $keterangan = $request->keterangantolak;

            $dataPengajuan = $this->service->getDataPengajuan($id_pengajuan);

            DB::beginTransaction();

            if ($dataPengajuan->id_statuspengajuan == 2 || $dataPengajuan->id_statuspengajuan == 5) {
                $this->service->tolakPengajuan($id_pengajuan); //ubah status pengajuan
                $this->service->tambahPersetujuan($id_pengajuan, $id_akses, 3, $idPihakpenyetuju, $keterangan);
            }

            DB::commit();

            return redirect(route('pengajuansurat.detail', $id_pengajuan))->with('success', 'Berhasil Tolak Pengajuan.');
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

    public function surveyKepuasan(Request $request){
        try {
            $request->validate([
                'id_pengajuan' => ['required'],
                'rating' => ['required', 'integer', 'between:1,5'],
                'keterangan' => ['nullable', 'string'],
            ]);

            $idPengajuan = $request->id_pengajuan;
            $keterangan = $request->keterangan;
            $rating = $request->rating;

            $dataPengajuan = $this->service->getDataPengajuan($idPengajuan);

            if (!empty($dataPengajuan->surveykepuasan)){
                return redirect(route('pengajuansurat.detail', $idPengajuan))->with('error', 'Anda sudah melakukan survey kepuasan.');
            }

            DB::beginTransaction();

            $idKepuasan = strtoupper(Uuid::uuid4()->toString());
            $this->service->simpanSurveyKepuasan($idKepuasan, $idPengajuan, $keterangan, $rating);

            DB::commit();

            return redirect(route('pengajuansurat.detail', $idPengajuan))->with('success', 'Berhasil mengisi Survey Kepuasan.');
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
