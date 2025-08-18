<?php

namespace App\Http\Repositories;

use App\Models\FilePengajuanRuangan;
use App\Models\FilePengajuanSurat;
use App\Models\Files;
use App\Models\JadwalRuangan;
use App\Models\JenisSurat;
use App\Models\PengajuanPeralatanRuangan;
use App\Models\PengajuanPersuratan;
use App\Models\PengajuanRuangan;
use App\Models\PengajuanRuanganDetail;
use App\Models\Pengaturan;
use App\Models\Pengumuman;
use App\Models\PersetujuanPersuratan;
use App\Models\PersetujuanRuangan;
use App\Models\Ruangan;
use App\Models\StatusPengaju;
use App\Models\SurveyKepuasanRuangan;
use App\Models\User;
use Carbon\Carbon;
use Ramsey\Uuid\Nonstandard\Uuid;

class PengajuanRuanganRepository
{
    public function getDataPengajuan($id_pengajuan, $id_akses){
        $data = PengajuanRuangan::with(['pihakpengaju','pihakupdater','statuspengaju','tahapanpengajuan','persetujuan','pengajuanruangandetail', 'pengajuanperalatandetail', 'pemeriksaawal', 'pemeriksaakhir', 'filepengajuanruangan', 'surveykepuasan']);

        $id_pengguna = auth()->user()->id;

        // khusus untuk akses pemeriksa dan pengguna, berdasarkan penunjukan atau yang mengajukan saja
        if (in_array($id_akses, [8,9])) {
            $data = $data->where(function ($q) use ($id_pengguna) {
                $q->where('pengaju', $id_pengguna) // sebagai pemohon
                ->orWhere('pemeriksa_awal', $id_pengguna)
                ->orWhere('pemeriksa_akhir', $id_pengguna);
            });
        }

        if ($id_akses == 3){ //admin ruangan
            $data = $data->where('id_tahapan', '!=', 1); // pengajuan tidak draft
        }

        if ($id_akses == 9){ //pemeriksa
            $data = $data->whereHas('persetujuan', function($q){
                $q->where('id_statuspersetujuan', 1)->where('id_tahapan', 2); //jika sudah disetujui pada tahapan verifikasi admin
            });
        }

        if ($id_akses == 6){ //kasubbag
            $data = $data->whereHas('persetujuan', function($q){
                $q->where('id_statuspersetujuan', 1)->where('id_tahapan', 3); //jika sudah disetujui pada tahapan pemeriksaan awal
            });
        }

        if ($id_akses == 7){ //kadep
            $data = $data->whereHas('persetujuan', function($q){
                $q->where('id_statuspersetujuan', 1)->where('id_tahapan', 4); //jika sudah disetujui pada tahapan verifikasi kasubbag
            });
        }

        $data = $data->orderBy('created_at', 'desc');

        if (!empty($id_pengajuan)) {
            return $data->where('id_pengajuan', $id_pengajuan)->first();
        }

        return $data;
    }

    public function getDataPengajuanOnly($idPengajuan){
        $data = PengajuanRuangan::with(['persetujuan'])->where('id_pengajuan', $idPengajuan)->first();

        return $data;
    }

    public function getDataStatusPeminjam(){
        $data = StatusPengaju::get();

        return $data;
    }

    public function getDataPengaturan(){
        $data = Pengaturan::first();

        return $data;
    }

    public function getDataRuangan($idRuangan){
        $data = Ruangan::whereIn('id_ruangan', $idRuangan)->get();

        return $data;
    }

    public function getDataRuanganAktif($idRuangan, $isEdit){
        $data = Ruangan::select('id_ruangan', 'nama', 'kode_ruangan');
        if (!empty($idRuangan)) {
            $data = $data->where('id', $idRuangan)->first();
        }else{
            if ($isEdit){
                $data = $data->where('is_aktif', 1)->get();
            }else{
                $data = $data->get();
            }
        }

        return $data;
    }

    public function getDataFile($idFile){
        $data = Files::find($idFile);

        return $data;
    }

    public function tambahDataPengajuan($idPengajuan, $tglMulai, $tglSelesai, $jamMulai, $jamSelesai, $statusPengaju, $deskripsiKegiatan, $namaKegiatan){
        PengajuanRuangan::create([
            'id_pengajuan' => $idPengajuan,
            'pengaju' => auth()->user()->id,
            'id_tahapan' => 1, //draft
            'id_statuspengaju' => $statusPengaju,
            'nama_kegiatan' => $namaKegiatan,
            'deskripsi' => $deskripsiKegiatan,
            'tgl_mulai' => Carbon::createFromFormat('d-m-Y', $tglMulai),
            'tgl_selesai' => Carbon::createFromFormat('d-m-Y', $tglSelesai),
            'jam_mulai' => Carbon::createFromFormat('H:i', $jamMulai),
            'jam_selesai' => Carbon::createFromFormat('H:i', $jamSelesai),
            'nama_pengaju' => auth()->user()->name,
            'no_hp' => auth()->user()->no_hp,
            'email' => auth()->user()->email,
            'email_its' => auth()->user()->email_its,
            'kartu_id' => auth()->user()->kartu_id,
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function tambahDataRuangan($idPengajuan, $ruangan){
        $idPengajuanDetail = strtoupper(Uuid::uuid4()->toString());

        PengajuanRuanganDetail::create([
            'id_pengajuanruangan_detail' => $idPengajuanDetail,
            'id_pengajuan' => $idPengajuan,
            'id_ruangan' => $ruangan,
        ]);
    }

    public function tambahDataPeralatan($idPengajuan, $alat, $jumlah){
        $idPengajuanDetail = strtoupper(Uuid::uuid4()->toString());

        PengajuanPeralatanRuangan::create([
            'id_pengajuanperalatan_ruang' => $idPengajuanDetail,
            'id_pengajuan' => $idPengajuan,
            'nama_sarana' => $alat,
            'jumlah' => $jumlah,
        ]);
    }

    public function updateTahapanPengajuan($idPengajuan, $idTahapan){
        $dataPengajuan = PengajuanRuangan::find($idPengajuan);
        $dataPengajuan->id_tahapan = $idTahapan;
        $dataPengajuan->save();
    }

    public function updatePemeriksaAwal($idPengajuan, $userPemeriksaAwal){
        $dataPengajuan = PengajuanRuangan::find($idPengajuan);
        $dataPengajuan->pemeriksa_awal = $userPemeriksaAwal;
        $dataPengajuan->save();
    }

    public function updatePemeriksaAkhir($idPengajuan, $userPemeriksaAkhir){
        $dataPengajuan = PengajuanRuangan::find($idPengajuan);
        $dataPengajuan->pemeriksa_akhir = $userPemeriksaAkhir;
        $dataPengajuan->save();
    }

    public function updatePengajuan($request){
        $id_pengajuan = $request->id_pengajuan;
        $idAkses = session('akses_default_id');

        $dataPengajuan = $this->getDataPengajuan($id_pengajuan, $idAkses);
        $dataUser = User::find($dataPengajuan->pengaju);

        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->nama_pengaju = $dataUser->name;
        $dataPengajuan->kartu_id = $dataUser->kartu_id;
        $dataPengajuan->no_hp = $dataUser->no_hp;
        $dataPengajuan->email = $dataUser->email;
        $dataPengajuan->email_its = $dataUser->email_its;
        $dataPengajuan->id_jenissurat = $request->jenis_surat;
        $dataPengajuan->keterangan = $request->keterangan;
        $dataPengajuan->data_form = $request->editor_surat;
        $dataPengajuan->updated_at = now();
        $dataPengajuan->updater = auth()->user()->id;
        $dataPengajuan->save();
    }

    public function updateDataPemohon($id_pengajuan){
        $idAkses = session('akses_default_id');

        $dataPengajuan = $this->getDataPengajuan($id_pengajuan, $idAkses);
        $dataUser = User::find($dataPengajuan->pengaju);

        $dataPengajuan = PengajuanRuangan::find($id_pengajuan);
        $dataPengajuan->nama_pengaju = $dataUser->name;
        $dataPengajuan->kartu_id = $dataUser->kartu_id;
        $dataPengajuan->no_hp = $dataUser->no_hp;
        $dataPengajuan->email = $dataUser->email;
        $dataPengajuan->email_its = $dataUser->email_its;
        $dataPengajuan->save();
    }

    public function hapusPengajuan($id_pengajuan){
        $pengajuan = PengajuanRuangan::where('id_pengajuan', $id_pengajuan)->where('id_tahapan', 1)->first();
        if ($pengajuan) {
            $pengajuan->delete();
        }
    }

    public function hapusDetailPengajuanRuangan($id_pengajuan){
        PengajuanRuanganDetail::where('id_pengajuan', $id_pengajuan)->delete();
    }

    public function hapusDetailPengajuanPeralatan($id_pengajuan){
        PengajuanPeralatanRuangan::where('id_pengajuan', $id_pengajuan)->delete();
    }

    public function getPersetujuanTerakhir($id_pengajuan, $id_akses){
        $data = PersetujuanPersuratan::with(['pihakpenyetuju','statuspersetujuan','akses'])
            ->where('id_pengajuan', $id_pengajuan)
            ->where('id_akses', $id_akses)
            ->orderBy('created_at', 'desc')
            ->first();

        return $data;
    }

    public function getPersetujuanTerakhirSuper($id_pengajuan){
        $data = PersetujuanPersuratan::with(['pihakpenyetuju','statuspersetujuan','akses'])
            ->where('id_pengajuan', $id_pengajuan)
            ->orderBy('created_at', 'desc')
            ->first();

        return $data;
    }

    public function ajukanPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 2;
        $dataPengajuan->save();
    }

    public function setujuiPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 1;
        $dataPengajuan->save();
    }

    public function revisiPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 4;
        $dataPengajuan->save();
    }

    public function sudahRevisiPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 5;
        $dataPengajuan->save();
    }

    public function tolakPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 3;
        $dataPengajuan->save();
    }

    public function tambahPersetujuan($idPengajuan, $idAkses, $idTahapan, $idStatusPersetujuan, $keterangan){
        $idPersetujuan = strtoupper(Uuid::uuid4()->toString());

        PersetujuanRuangan::create([
            'id_persetujuan' => $idPersetujuan,
            'id_pengajuan' => $idPengajuan,
            'id_tahapan' => $idTahapan,
            'id_statuspersetujuan' => $idStatusPersetujuan,
            'id_akses' => $idAkses,
            'penyetuju' => auth()->user()->id,
            'nama_penyetuju' => auth()->user()->name,
            'keterangan' => $keterangan,
            'created_at' => now()
        ]);
    }

    public function cekJadwalRuanganBentrok($idRuangan, $tglMulai, $tglSelesai, $jamMulai, $jamSelesai){
        // Parse input tanggal
        $tglMulai = Carbon::createFromFormat('d-m-Y', $tglMulai)->format('Y-m-d');
        $tglSelesai = Carbon::createFromFormat('d-m-Y', $tglSelesai)->format('Y-m-d');

        // Parse input jam
        $jamMulai = Carbon::createFromFormat('H:i', $jamMulai)->format('H:i:s');
        $jamSelesai = Carbon::createFromFormat('H:i', $jamSelesai)->format('H:i:s');

//        $jadwalBentrok = JadwalRuangan::whereIn('id_ruangan', $idRuangan)
//            ->where(function($query) use ($tglMulai, $tglSelesai, $jamMulai, $jamSelesai) {
//                $query->whereBetween('tgl_mulai', [$tglMulai, $tglSelesai])
//                    ->orWhereBetween('tgl_selesai', [$tglMulai, $tglSelesai])
//                    ->orWhere(function($q) use ($tglMulai, $tglSelesai) {
//                        $q->where('tgl_mulai', '<=', $tglMulai)
//                            ->where('tgl_selesai', '>=', $tglSelesai);
//                    });
//            })
//            ->where(function($query) use ($jamMulai, $jamSelesai) {
//                $query->where('jam_mulai', '<', $jamSelesai)
//                    ->where('jam_selesai', '>', $jamMulai);
//            });
//
//        $jadwalBentrok = $jadwalBentrok->exists();

        // Cari semua hari yang ada di range input
        $daysInRange = [];
        $period = new \DatePeriod(new \DateTime($tglMulai), new \DateInterval('P1D'), (new \DateTime($tglSelesai))->modify('+1 day'));
        foreach ($period as $date) {
            $phpDay = (int) $date->format('N');
            $mappedDay = ($phpDay % 7) + 1;
            $daysInRange[] = $mappedDay;
        }

        $jadwalBentrok = JadwalRuangan::whereIn('id_ruangan', (array) $idRuangan)
            ->where('tgl_mulai', '<=', $tglSelesai)
            ->where('tgl_selesai', '>=', $tglMulai)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->whereIn('day_of_week', $daysInRange)
            ->exists();

        return $jadwalBentrok;
    }

    public function getDataJadwal($idRuangan){
        $data = JadwalRuangan::with('ruangan')->whereIn('id_ruangan', $idRuangan)->get();

        return $data;
    }

    public function getUserPemeriksaRuangan($search){
        $availableUsers = User::whereHas('aksesuser', function($q){
                $q->where('id_akses', 9); //jika sudah disetujui pada tahapan verifikasi kasubbag
            })->when($search, fn($q) => $q->where('name', 'like', "%$search%"))->where('email_verified_at', '!=', null)
            ->limit(10)
            ->get(['id', 'name']);

        return $availableUsers;
    }

    public function getDataPeralatan($idPengajuan){
        $data = PengajuanPeralatanRuangan::where('id_pengajuan', $idPengajuan)->get();

        return $data;
    }

    public function updateStatusPemeriksaan($status, $id_pengajuanperalatan_ruang, $kondisi, $keterangan){
        $dataPeralatan = PengajuanPeralatanRuangan::find($id_pengajuanperalatan_ruang);
        if ($dataPeralatan){
            $dataPeralatan->update([
                'is_valid_'.$status => $kondisi,
                'keterangan_'.$status => $keterangan
            ]);
        }
    }

    public function tambahFile($id_file, $fileName, $filePath, $fileMime, $fileExt, $fileSize){
        $file = Files::find($id_file);

        if (!$file) {
            Files::create([
                'id_file' => $id_file,
                'file_name' => $fileName,
                'location' => $filePath,
                'mime' => $fileMime,
                'ext' => $fileExt,
                'file_size' => $fileSize,
                'created_at' => now(),
                'is_private' => 0,
                'updater' => auth()->user()->id
            ]);
        }
    }

    public function updateFileRuangan($idPengajuan, $idFile){
        FilePengajuanRuangan::create([
            'id_pengajuan' => $idPengajuan,
            'id_file' => $idFile,
        ]);
    }

    public function simpanSurveyKepuasan($idKepuasan, $idPengajuan, $keterangan, $rating){
        SurveyKepuasanRuangan::create([
            'id_kepuasan' => $idKepuasan,
            'id_pengajuan' => $idPengajuan,
            'rating' => $rating,
            'keterangan' => $keterangan
        ]);
    }

    public function simpanJadwalRuangan($record){
        JadwalRuangan::create($record);
    }
}
