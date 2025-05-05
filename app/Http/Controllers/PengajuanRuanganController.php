<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengajuanRuanganRepository;
use App\Http\Services\PengajuanRuanganServices;
use App\Rules\CekHariDalamRange;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Nonstandard\Uuid;
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
                ->addColumn('namaruangan', function ($data_pengajuan) {
                    $html = '<b class="small">';
                    foreach ($data_pengajuan->pengajuanruangandetail as $ruangan){
                        $html .= '&bullet;&nbsp;' . $ruangan->ruangan->nama . '<br>';
                    }

                    $html .= '</b>';

                    return $html;
                })
                ->addColumn('tglbooking', function ($data_pengajuan) {
                    $tanggalMulai = Carbon::parse($data_pengajuan->tgl_mulai)->translatedFormat('d F Y');
                    $tanggalSelesai = Carbon::parse($data_pengajuan->tgl_selesai)->translatedFormat('d F Y');
                    $jamMulai = Carbon::parse($data_pengajuan->jam_mulai)->translatedFormat('H:i');
                    $jamSelesai = Carbon::parse($data_pengajuan->jam_selesai)->translatedFormat('H:i');

                    if ($data_pengajuan->tgl_mulai == $data_pengajuan->tgl_selesai) {
                        return "<i class='text-muted small'>$tanggalMulai, jam $jamMulai – $jamSelesai</i>";
                    } else {
                        return "<i class='text-muted small'>$tanggalMulai s/d $tanggalSelesai, <br>jam $jamMulai – $jamSelesai</i>";
                    }
                })
                ->addColumn('pengaju', function ($data_pengajuan) {
                    return '<span style="font-size: smaller;">'.$data_pengajuan->nama_pengaju.
                        ',<br><span class="small"><i><b>'.$data_pengajuan->statuspengaju->nama.'</b></i></span>';
                })
                ->addColumn('namakegiatan', function ($data_pengajuan) {
                    return '<span class="text-muted" style="font-size: smaller; font-style: italic">'.$data_pengajuan->nama_kegiatan.'</span>';
                })
                ->addColumn('status', function ($data_pengajuan) use($id_akses) {
                    $html = '<span style="font-size: smaller; color: '.$data_pengajuan->statuspengajuan->html_color.'">'.$data_pengajuan->statuspengajuan->nama.'</span>';
                    $html .= $this->service->getHtmlStatusPengajuan($data_pengajuan->id_statuspengajuan, $id_akses, $data_pengajuan->persetujuan);

                    return $html;
                })
                ->addColumn('aksi', function ($data_pengajuan) {
                    $html = '<a href="'.route('pengajuanruangan.detail', $data_pengajuan->id_pengajuan).'" class="btn btn-sm py-1 px-2 btn-primary"><span class="bx bx-edit-alt"></span><span class="d-none d-lg-inline-block">&nbsp;Detail</span></a>';
                    if ($data_pengajuan->id_statuspengajuan == 0) { //status draft bisa hapus
                        $html .= '&nbsp;&nbsp;<a href="javascript:;" data-id="' . $data_pengajuan->id_pengajuan . '" data-bs-toggle="modal" data-bs-target="#modal-hapus" class="btn btn-sm py-1 px-2 btn-danger"><span class="bx bx-trash"></span><span class="d-none d-lg-inline-block">&nbsp;Hapus</span></a>';
                    }

                    return $html;
                })
                ->rawColumns(['namaruangan', 'tglbooking', 'aksi', 'namakegiatan', 'pengaju', 'status']) // Untuk render tombol HTML
                ->filterColumn('namaruangan', function($query, $keyword) {
                    $query->whereHas('pengajuanruangandetail', function ($q) use ($keyword) {
                        $q->where('pengajuanruangandetail.nama', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('namakegiatan', function($query, $keyword) {
                    $query->where('pengajuan_ruangan.nama_kegiatan', 'LIKE', "%{$keyword}%");
                })
                ->filterColumn('pengaju', function($query, $keyword) {
                    $query->where('pengajuan_ruangan.nama_pengaju', 'LIKE', "%{$keyword}%");
                })
                ->toJson();
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function tambahPengajuan(){
        $title = "Tambah Pengajuan";

        $dataStatusPeminjam = $this->service->getDataStatusPeminjam();
        $dataRuangan = $this->service->getDataRuanganAktif(isEdit: true);

        return view('pages.pengajuan_ruangan.tambah', compact('title', 'dataStatusPeminjam', 'dataRuangan'));
    }

    public function cekDataJadwal(Request $request){
        try {
            $request->validate([
                'id_ruangan' => ['required'],
                'jam_mulai' => ['required', 'date_format:H:i'],
                'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
                'tanggal_booking' => [
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
                ]
            ],[
                'id_ruangan.required' => 'Id ruangan wajib diisi.',
                'tanggal_booking.required' => 'Data tanggal wajib diisi.',
                'jam_mulai.required' => 'Jam mulai wajib diisi.',
                'jam_mulai.date_format' => 'Format jam mulai harus HH:MM (contoh: 14:30).',
                'jam_selesai.required' => 'Jam selesai wajib diisi.',
                'jam_selesai.date_format' => 'Format jam selesai harus HH:MM (contoh: 14:30).',
                'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.'
            ]);

            $isEdit = $this->service->checkAksesTambah(Auth()->user()->id_akses);
            $idRuangan = $request->id_ruangan;
            if (!$isEdit) {
                return response()->json(['status' => false, 'dataRuangan' => []]);
            }

            $dataJadwal = explode(' s/d ', $request->tanggal_booking);
            $tgl_mulai = $dataJadwal[0];
            $tgl_selesai = $dataJadwal[1];
            $jam_mulai = $request->jam_mulai;
            $jam_selesai = $request->jam_selesai;

            $cekJadwalBentrok = $this->service->cekJadwalRuanganBentrok($idRuangan, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai);
            if ($cekJadwalBentrok) {
                return response()->json(['status' => false, 'dataRuangan' => []]);
            }else{
                $dataRuangan = $this->service->getDataRuangan($idRuangan);
                return response()->json(['status' => true, 'dataRuangan' => $dataRuangan]);
            }
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'dataRuangan' => []]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'dataRuangan' => []]);
        }
    }

    public function doTambahPengajuan(Request $request){
        try {
            $request->validate([
                'status_peminjam' => ['required'],
                'ruangan' => ['required','array'],
                'jam_mulai' => ['required', 'date_format:H:i'],
                'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
                'tanggal_booking' => [
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
                'nama_kegiatan' => ['required'],
                'deskripsi_kegiatan' => ['required'],
                'peralatan_nama' => ['required','array'],
                'peralatan_jumlah' => ['required','array']
            ],[
                'status_peminjam.required' => 'Status Peminjam wajib diisi.',
                'ruangan.required' => 'Ruangan wajib diisi.',
                'ruangan.array' => 'Ruangan tidak valid.',
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'deskripsi_kegiatan.required' => 'Deskripsi kegiatan wajib diisi.',
                'tanggal_booking.required' => 'Data tanggal wajib diisi.',
                'jam_mulai.required' => 'Jam mulai wajib diisi.',
                'jam_mulai.date_format' => 'Format jam mulai harus HH:MM (contoh: 14:30).',
                'jam_selesai.required' => 'Jam selesai wajib diisi.',
                'jam_selesai.date_format' => 'Format jam selesai harus HH:MM (contoh: 14:30).',
                'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
                'peralatan_nama.required' => 'Nama peralatan wajib diisi.',
                'peralatan_nama.array' => 'Nama peralatan tidak valid.',
                'peralatan_jumlah.required' => 'Jumlah peralatan wajib diisi.',
                'peralatan_jumlah.array' => 'Jumlah peralatan tidak valid.',
            ]);

            $isTambah = $this->service->checkAksesTambah(Auth()->user()->id_akses);
            if (!$isTambah) {
                return redirect(route('pengajuanruangan'))->with('error', 'Anda tidak punya otoritas.');
            }

            $dataJadwal = explode(' s/d ', $request->tanggal_booking);
            $tgl_mulai = $dataJadwal[0];
            $tgl_selesai = $dataJadwal[1];
            $jam_mulai = $request->jam_mulai;
            $jam_selesai = $request->jam_selesai;
            $idRuangan = $request->ruangan;
            $peralatan = $request->peralatan_nama;
            $jumlahPeralatan = $request->peralatan_jumlah;

            $cekJadwalBentrok = $this->service->cekJadwalRuanganBentrok($idRuangan, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai);

            if ($cekJadwalBentrok){
                return redirect(route('pengajuanruangan.tambah'))->with('error', 'Jadwal yang diinputkan bentrok dengan jadwal yang sudah ada.');
            }

            DB::beginTransaction();

            $idPengajuan = strtoupper(Uuid::uuid4()->toString());

            $this->service->tambahDataPengajuan($idPengajuan, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai, $request->status_peminjam, $request->deskripsi_kegiatan, $request->nama_kegiatan);
            $this->service->tambahDataRuangan($idPengajuan, $idRuangan);
            $this->service->tambahDataPeralatan($idPengajuan, $peralatan, $jumlahPeralatan);

            DB::commit();

            return redirect(route('pengajuanruangan.detail', $idPengajuan))->with('success', 'Berhasil Tambah Pengajuan.');
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

    public function detailPengajuan($idPengajuan){
        $title = "Detail Pengajuan";

        $dataPengajuan = $this->service->getDataPengajuan($idPengajuan);
        $isEdit = $this->service->checkOtoritasPengajuan($dataPengajuan->id_statuspengajuan);
        $dataStatusPeminjam = $this->service->getDataStatusPeminjam();
        $dataRuangan = $this->service->getDataRuanganAktif(isEdit: $isEdit);

        //$isEdit = false;
        if ($isEdit){
            //update data pemohon pengajuan
            try {
                DB::beginTransaction();

                $this->service->updateDataPemohon($idPengajuan);

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        $statusVerifikasi = $this->service->getStatusVerifikasi($idPengajuan);

        return view('pages.pengajuan_ruangan.detail', compact('dataPengajuan', 'idPengajuan', 'isEdit', 'statusVerifikasi', 'dataStatusPeminjam', 'dataRuangan', 'title'));
    }
}
