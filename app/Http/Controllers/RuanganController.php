<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RuanganRepository;
use App\Http\Services\RuanganServices;
use App\Rules\CekHariDalamRange;
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
    private $idAkses;
    public function __construct(){
        $this->service = new RuanganServices(new RuanganRepository());
        $this->idAkses = session('akses_default_id');
    }
    public function index(){
        $title = "Ruangan";
        $isTambah = $this->service->checkAksesTambah($this->idAkses);
        $dataRuangan = $this->service->getDataRuangan();

        return view('pages.ruangan.index', compact('title', 'dataRuangan','isTambah'));
    }

    public function tambahRuangan(){
        $title = "Tambah Ruangan";
        $dataFasilitas = config('listfasilitas', []);
        $dataJenisRuangan = $this->service->getJenisRuangan();

        return view('pages.ruangan.tambah', compact('title','dataFasilitas','dataJenisRuangan'));
    }

    public function doTambahRuangan(Request $request){
        try {
            $request->validate([
                'kode_ruangan' => ['required', Rule::unique('ruangan', 'kode_ruangan')],
                'nama_ruangan' => ['required'],
                'lokasi' => ['required'],
                'jenis_ruangan' => ['required'],
                'kapasitas' => ['required', 'integer'],
                'fasilitas' => ['required'],
                'keterangan' => ['required'],
                'gambar_ruangan' => ['required', 'file', 'image', 'max:5048']
            ],[
                'kode_ruangan.required' => 'Kode ruangan wajib diisi.',
                'kode_ruangan.unique' => 'Kode ruangan sudah terdaftar.',
                'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
                'lokasi.required' => 'Lokasi wajib diisi.',
                'jenis_ruangan.required' => 'Jenis ruangan wajib diisi.',
                'kapasitas.required' => 'Kapasitas wajib diisi.',
                'kapasitas.integer' => 'Kapasitas harus numeric.',
                'fasilitas.required' => 'Fasilitas wajib diisi.',
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
        $isEdit = $this->service->checkAksesEdit($this->idAkses);
        $dataRuangan = $this->service->getDataRuangan($idRuangan);
        $dataFasilitas = config('listfasilitas', []);
        $dataJenisRuangan = $this->service->getJenisRuangan();

        if ($isEdit){
            return view('pages.ruangan.edit', compact('title','dataRuangan','idRuangan','dataFasilitas','dataJenisRuangan'));
        }else{
            return view('pages.ruangan.detail', compact('title','dataRuangan'));
        }
    }

    public function doUpdateRuangan(Request $request){
        try {
            $request->validate([
                'kode_ruangan' => ['required', Rule::unique('ruangan', 'kode_ruangan')->ignore($request->id_ruangan,'id_ruangan')],
                'nama_ruangan' => ['required'],
                'lokasi' => ['required'],
                'jenis_ruangan' => ['required'],
                'kapasitas' => ['required', 'integer'],
                'fasilitas' => ['required'],
                'keterangan' => ['required'],
                'gambar_ruangan' => ['file', 'image', 'max:5048']
            ],[
                'kode_ruangan.required' => 'Kode ruangan wajib diisi.',
                'kode_ruangan.unique' => 'Kode ruangan sudah terdaftar.',
                'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
                'jenis_ruangan.required' => 'Jenis ruangan wajib diisi.',
                'lokasi.required' => 'Lokasi wajib diisi.',
                'kapasitas.required' => 'Kapasitas wajib diisi.',
                'kapasitas.integer' => 'Kapasitas harus numeric.',
                'fasilitas.required' => 'Deskripsi wajib diisi.',
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

    public function jadwalRuangan($idRuangan){
        $title = "Jadwal Ruangan";
        $isEdit = $this->service->checkAksesEdit($this->idAkses);
        $dataRuangan = $this->service->getDataRuangan($idRuangan);
        $hari = [
            2 => 'Senin',
            3 => 'Selasa',
            4 => 'Rabu',
            5 => 'Kamis',
            6 => 'Jumat',
            7 => 'Sabtu',
            1 => 'Minggu',
        ];

        if ($dataRuangan->is_aktif == 0){
            return redirect(route('ruangan'))->with('error', "Ruangan sudah tidak aktif.");
        }

        return view('pages.ruangan.jadwal', compact('title','dataRuangan','idRuangan','isEdit','hari'));
    }

    public function getDataJadwal(Request $request){
        $idRuangan = $request->id_ruangan;
        $dataJadwal = $this->service->getDataJadwal($idRuangan);
        $dataBooking = [];

        $data = [
            'jadwal' => $dataJadwal,
            'booking' => $dataBooking,
        ];

        return response()->json($data);
    }

    public function doTambahJadwal(Request $request){
        try {
            $request->validate([
                'idRuangan' => ['required'],
                'keterangan' => ['required'],
                'jam_mulai' => ['required', 'date_format:H:i'],
                'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
                'tgl_jadwal' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $parts = explode(' s/d ', $value);

                        // Cek harus ada 2 tanggal
                        if (count($parts) !== 2) {
                            return $fail('Format tanggal harus "dd-mm-YYYY s/d dd-mm-YYYY".');
                        }

                        foreach ($parts as $part) {
                            $date = \DateTime::createFromFormat('d-m-Y', trim($part));
                            if (!$date) {
                                return $fail('Tanggal harus dalam format dd-mm-YYYY.');
                            }

                            // Tambahan pengecekan error format
                            $errors = \DateTime::getLastErrors();
                            if (!empty($errors['warning_count']) || !empty($errors['error_count'])) {
                                return $fail('Tanggal tidak valid.');
                            }
                        }
                    }
                ],
                'hari' => ['required', 'integer', 'between:1,7',
                    new CekHariDalamRange($request->tgl_jadwal)
                ],
            ],[
                'idRuangan.required' => 'Id ruangan wajib diisi.',
                'keterangan.required' => 'Keterangan jadwal wajib diisi.',
                'hari.required' => 'Hari wajib diisi.',
                'hari.integer' => 'Hari tidak valid.',
                'hari.between' => 'Hari harus antara senin - minggu.',
                'tgl_jadwal.required' => 'Data tanggal wajib diisi.',
                'jam_mulai.required' => 'Jam mulai wajib diisi.',
                'jam_mulai.date_format' => 'Format jam mulai harus HH:MM (contoh: 14:30).',
                'jam_selesai.required' => 'Jam selesai wajib diisi.',
                'jam_selesai.date_format' => 'Format jam selesai harus HH:MM (contoh: 14:30).',
                'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.'
            ]);

            $isEdit = $this->service->checkAksesEdit($this->idAkses);
            $idRuangan = $request->idRuangan;
            if (!$isEdit) {
                return redirect(route('ruangan.jadwal', $idRuangan))->with('error', 'Anda tidak punya otoritas.');
            }

            $dataJadwal = explode(' s/d ', $request->tgl_jadwal);
            $tgl_mulai = $dataJadwal[0];
            $tgl_selesai = $dataJadwal[1];
            $jam_mulai = $request->jam_mulai;
            $jam_selesai = $request->jam_selesai;

            $cekJadwalBentrok = $this->service->cekJadwalRuanganBentrok($idRuangan, $request->hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai);

            if ($cekJadwalBentrok){
                return redirect(route('ruangan.jadwal', $idRuangan))->with('error', 'Jadwal yang diinputkan bentrok dengan jadwal yang sudah ada.');
            }

            DB::beginTransaction();

            $idJadwal = strtoupper(Uuid::uuid4()->toString());

            $this->service->tambahJadwalRuangan($idJadwal, $idRuangan, $request->keterangan, $request->hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai);

            DB::commit();

            return redirect(route('ruangan.jadwal', $idRuangan))->with('success', 'Berhasil Tambah Jadwal.');
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

    public function doUpdateJadwal(Request $request){
        try {
            $request->validate([
                'idRuangan' => ['required'],
                'idJadwal' => ['required'],
                'keterangan' => ['required'],
                'jam_mulai' => ['required', 'date_format:H:i'],
                'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
                'tgl_jadwal' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $parts = explode(' s/d ', $value);

                        // Cek harus ada 2 tanggal
                        if (count($parts) !== 2) {
                            return $fail('Format tanggal harus "dd-mm-YYYY s/d dd-mm-YYYY".');
                        }

                        foreach ($parts as $part) {
                            $date = \DateTime::createFromFormat('d-m-Y', trim($part));
                            if (!$date) {
                                return $fail('Tanggal harus dalam format dd-mm-YYYY.');
                            }

                            // Tambahan pengecekan error format
                            $errors = \DateTime::getLastErrors();
                            if (!empty($errors['warning_count']) || !empty($errors['error_count'])) {
                                return $fail('Tanggal tidak valid.');
                            }
                        }
                    }
                ],
                'hari' => ['required', 'integer', 'between:1,7',
                    new CekHariDalamRange($request->tgl_jadwal)
                ],
            ],[
                'idRuangan.required' => 'Id ruangan wajib diisi.',
                'idJadwal.required' => 'Id jadwal wajib diisi.',
                'keterangan.required' => 'Keterangan jadwal wajib diisi.',
                'hari.required' => 'Hari wajib diisi.',
                'hari.integer' => 'Hari tidak valid.',
                'hari.between' => 'Hari harus antara senin - minggu.',
                'tgl_jadwal.required' => 'Data tanggal wajib diisi.',
                'jam_mulai.required' => 'Jam mulai wajib diisi.',
                'jam_mulai.date_format' => 'Format jam mulai harus HH:MM (contoh: 14:30).',
                'jam_selesai.required' => 'Jam selesai wajib diisi.',
                'jam_selesai.date_format' => 'Format jam selesai harus HH:MM (contoh: 14:30).',
                'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.'
            ]);

            $isEdit = $this->service->checkAksesEdit($this->idAkses);
            $idRuangan = $request->idRuangan;
            if (!$isEdit) {
                return redirect(route('ruangan.jadwal', $idRuangan))->with('error', 'Anda tidak punya otoritas.');
            }

            $idJadwal = $request->idJadwal;
            $dataJadwal = explode(' s/d ', $request->tgl_jadwal);
            $tgl_mulai = $dataJadwal[0];
            $tgl_selesai = $dataJadwal[1];
            $jam_mulai = $request->jam_mulai;
            $jam_selesai = $request->jam_selesai;

            $cekJadwalBentrok = $this->service->cekJadwalRuanganBentrok($idRuangan, $request->hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai, $idJadwal);
            $dataJadwal = $this->service->getDataJadwalByIdJadwal($idJadwal);

            if ($dataJadwal->tipe_jadwal == 'jadwal') {
                if ($cekJadwalBentrok) {
                    return redirect(route('ruangan.jadwal', $idRuangan))->with('error', 'Jadwal yang diinputkan bentrok dengan jadwal yang sudah ada.');
                }

                DB::beginTransaction();

                $this->service->updateJadwalRuangan($idJadwal, $request->keterangan, $request->hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai);

                DB::commit();

                return redirect(route('ruangan.jadwal', $idRuangan))->with('success', 'Berhasil Update Jadwal.');
            }else{
                return redirect(route('ruangan.jadwal', $idRuangan))->with('error', 'Jadwal Booking tidak bisa diubah.');
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

    public function doHapusJadwal(Request $request){
        try {
            $request->validate([
                'idRuangan' => ['required'],
                'idJadwal' => ['required']
            ],[
                'idRuangan.required' => 'Id ruangan wajib diisi.',
                'idJadwal.required' => 'Id jadwal wajib diisi.'
            ]);

            $isEdit = $this->service->checkAksesEdit($this->idAkses);
            $idRuangan = $request->idRuangan;
            if (!$isEdit) {
                return redirect(route('ruangan.jadwal', $idRuangan))->with('error', 'Anda tidak punya otoritas.');
            }

            $idJadwal = $request->idJadwal;
            $dataJadwal = $this->service->getDataJadwalByIdJadwal($idJadwal);

            if ($dataJadwal->tipe_jadwal == 'jadwal') {
                DB::beginTransaction();

                $this->service->hapusJadwalRuangan($idJadwal);

                DB::commit();

                return redirect(route('ruangan.jadwal', $idRuangan))->with('success', 'Berhasil Hapus Jadwal.');
            }else{
                return redirect(route('ruangan.jadwal', $idRuangan))->with('error', 'Jadwal Booking tidak bisa dihapus.');
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
}
