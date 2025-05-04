<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengajuanRuanganRepository;
use App\Http\Services\PengajuanRuanganServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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

        $dataStatusPeminjam = $this->service->getDataStatusPeminjam();
        $dataRuangan = $this->service->getDataRuanganAktif();

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
        dd($request->input());
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
}
