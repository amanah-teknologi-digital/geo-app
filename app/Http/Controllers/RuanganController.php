<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RuanganRepository;
use App\Http\Services\RuanganServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Nonstandard\Uuid;

class RuanganController extends Controller
{
    private $service;
    public function __construct(){
        $this->service = new RuanganServices(new RuanganRepository());
    }
    public function index(){
        $title = "Ruangan";
        $isTambah = $this->service->checkAksesTambah(Auth()->user()->id_akses);
        $dataRuangan = $this->service->getDataRuangan();

        return view('pages.ruangan.index', compact('title', 'dataRuangan','isTambah'));
    }

    public function tambahRuangan(){
        $title = "Tambah Ruangan";
        $dataFasilitas = config('listfasilitas', []);

        return view('pages.ruangan.tambah', compact('title','dataFasilitas'));
    }

    public function doTambahRuangan(Request $request){
        try {
            $request->validate([
                'kode_ruangan' => ['required', Rule::unique('ruangan', 'kode_ruangan')],
                'nama_ruangan' => ['required'],
                'lantai' => ['required', 'integer'],
                'kapasitas' => ['required', 'integer'],
                'deskripsi' => ['required'],
                'keterangan' => ['required'],
                'gambar_ruangan' => ['required', 'file', 'image', 'max:5048']
            ],[
                'kode_ruangan.required' => 'Kode ruangan wajib diisi.',
                'kode_ruangan.unique' => 'Kode ruangan sudah terdaftar.',
                'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
                'lantai.required' => 'Lantai wajib diisi.',
                'lantai.integer' => 'Lantai harus numeric.',
                'kapasitas.required' => 'Kapasitas wajib diisi.',
                'kapasitas.integer' => 'Kapasitas harus numeric.',
                'deskripsi.required' => 'Deskripsi wajib diisi.',
                'keterangan.required' => 'Keterangan wajib diisi.',
                'gambar_ruangan.required' => 'Gambar Ruangan wajib diisi.',
                'gambar_ruangan.file' => 'File yang diunggah tidak valid.',
                'gambar_ruangan.image' => 'File harus berupa gambar.',
                'gambar_ruangan.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]);

            DB::beginTransaction();
            //save file gambar
            $idFileGambar = strtoupper(Uuid::uuid4()->toString());
            $idRuangan = strtoupper(Uuid::uuid4()->toString());
            $this->service->tambahFile($request->file('gambar_ruangan'), $idFileGambar);
            $this->service->tambahRuangan($request, $idRuangan, $idFileGambar);

            DB::commit();

            return redirect(route('ruangan.detail', $idRuangan))->with('success', 'Berhasil Tambah Ruangan.');
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

    public function detailRuangan($idRuangan){
        $title = "Detail Ruangan";
        $isEdit = $this->service->checkAksesEdit(Auth()->user()->id_akses);
        $dataRuangan = $this->service->getDataRuangan($idRuangan);

        if ($isEdit){
            return view('pages.ruangan.edit', compact('title','dataRuangan','idRuangan'));
        }else{
            return view('pages.ruangan.detail', compact('title','dataRuangan'));
        }
    }

    public function doUpdateRuangan(Request $request){
        try {
            $request->validate([
                'kode_ruangan' => ['required', Rule::unique('ruangan', 'kode_ruangan')->ignore($request->id_ruangan,'id_ruangan')],
                'nama_ruangan' => ['required'],
                'lantai' => ['required', 'integer'],
                'kapasitas' => ['required', 'integer'],
                'deskripsi' => ['required'],
                'keterangan' => ['required'],
                'gambar_ruangan' => ['file', 'image', 'max:5048']
            ],[
                'kode_ruangan.required' => 'Kode ruangan wajib diisi.',
                'kode_ruangan.unique' => 'Kode ruangan sudah terdaftar.',
                'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
                'lantai.required' => 'Lantai wajib diisi.',
                'lantai.integer' => 'Lantai harus numeric.',
                'kapasitas.required' => 'Kapasitas wajib diisi.',
                'kapasitas.integer' => 'Kapasitas harus numeric.',
                'deskripsi.required' => 'Deskripsi wajib diisi.',
                'keterangan.required' => 'Keterangan wajib diisi.',
                'gambar_ruangan.file' => 'File yang diunggah tidak valid.',
                'gambar_ruangan.image' => 'File harus berupa gambar.',
                'gambar_ruangan.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]);

            $dataRuangan = $this->service->getDataRuangan($request->id_ruangan);

            DB::beginTransaction();
            //save file gambar
            $idFileGambar = $dataRuangan->gambar_file;
            if ($request->hasFile('gambar_ruangan')) {
                $this->service->tambahFile($request->file('gambar_ruangan'), $idFileGambar);
            }

            $idRuangan = $request->id_ruangan;
            $this->service->updateRuangan($request, $idRuangan);

            DB::commit();

            return redirect(route('ruangan.detail', $idRuangan))->with('success', 'Berhasil Update Ruangan.');
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
