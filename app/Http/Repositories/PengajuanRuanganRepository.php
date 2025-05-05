<?php

namespace App\Http\Repositories;

use App\Models\FilePengajuanSurat;
use App\Models\Files;
use App\Models\JadwalRuangan;
use App\Models\JenisSurat;
use App\Models\PengajuanPeralatanRuangan;
use App\Models\PengajuanPersuratan;
use App\Models\PengajuanRuangan;
use App\Models\PengajuanRuanganDetail;
use App\Models\Pengumuman;
use App\Models\PersetujuanPersuratan;
use App\Models\Ruangan;
use App\Models\StatusPengaju;
use App\Models\User;
use Carbon\Carbon;
use Ramsey\Uuid\Nonstandard\Uuid;

class PengajuanRuanganRepository
{
    public function getDataPengajuan($id_pengajuan, $id_akses){
        $data = PengajuanRuangan::with(['pihakpengaju','pihakupdater','statuspengaju','statuspengajuan','persetujuan','pengajuanruangandetail', 'pengajuanperalatandetail']);

        $id_pengguna = auth()->user()->id;
        if ($id_akses == 8){ //pengguna
            $data = $data->where('pengaju', $id_pengguna)
                ->orderByRaw('CASE
                    WHEN id_statuspengajuan = 0 THEN 0
                    WHEN id_statuspengajuan = 4 THEN 1
                    ELSE 2
                    END');
        }

        if ($id_akses == 2){ // admin geo harus status tidak draft
            $data = $data->where('id_statuspengajuan', '!=', 0)
                ->orderByRaw('IF(EXISTS(SELECT 1 FROM persetujuan_ruangan WHERE persetujuan_ruangan.id_pengajuan = pengajuan_ruangan.id_pengajuan AND persetujuan_ruangan.id_akses = 2), 1, 0)') // Urutkan yang tidak ada id_akses = 2 ke atas
                ->orderByRaw('CASE
                    WHEN id_statuspengajuan = 5 THEN 0
                    ELSE 1
                    END');
        }

        if ($id_akses == 1){ //super admin
            $data = $data
                ->orderByRaw('IF(EXISTS(SELECT 1 FROM persetujuan_ruangan WHERE persetujuan_ruangan.id_pengajuan = pengajuan_ruangan.id_pengajuan AND persetujuan_ruangan.id_akses = 2), 1, 0)') // Urutkan yang tidak ada id_akses = 2 ke atas
                ->orderByRaw('CASE
                    WHEN id_statuspengajuan = 0 THEN 0
                    WHEN id_statuspengajuan = 4 THEN 1
                    WHEN id_statuspengajuan = 5 THEN 2
                    ELSE 3
                    END'); // Urutkan dengan id_statuspengajuan 0, 4, 5
        }

        $data = $data->orderBy('created_at', 'desc');

        if (!empty($id_pengajuan)) {
            $data = $data->where('id_pengajuan', $id_pengajuan)->first();
        }

        return $data;
    }

    public function getDataStatusPeminjam(){
        $data = StatusPengaju::get();

        return $data;
    }

    public function getDataRuangan($idRuangan){
        $data = Ruangan::whereIn('id_ruangan', $idRuangan)->get();

        return $data;
    }

    public function getDataRuanganAktif($idRuangan, $isEdit){
        $data = Ruangan::select('id_ruangan', 'nama');
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
            'id_statuspengajuan' => 0, //draft
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

    public function updatePengajuan($request){
        $id_pengajuan = $request->id_pengajuan;

        $dataPengajuan = $this->getDataPengajuan($id_pengajuan, auth()->user()->id_akses);
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
        $dataPengajuan = $this->getDataPengajuan($id_pengajuan, auth()->user()->id_akses);
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
        $pengajuan = PengajuanRuangan::where('id_pengajuan', $id_pengajuan)->where('id_statuspengajuan', 0)->first();
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

    public function tambahPersetujuan($id_pengajuan, $id_akses, $id_statuspersetujuan, $keterangan){
        $id_persetujuan = strtoupper(Uuid::uuid4()->toString());

        PersetujuanPersuratan::create([
            'id_persetujuan' => $id_persetujuan,
            'id_pengajuan' => $id_pengajuan,
            'id_statuspersetujuan' => $id_statuspersetujuan,
            'id_akses' => $id_akses,
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

        $jadwalBentrok = JadwalRuangan::whereIn('id_ruangan', $idRuangan)
            ->where(function($query) use ($tglMulai, $tglSelesai, $jamMulai, $jamSelesai) {
                $query->whereBetween('tgl_mulai', [$tglMulai, $tglSelesai])
                    ->orWhereBetween('tgl_selesai', [$tglMulai, $tglSelesai])
                    ->orWhere(function($q) use ($tglMulai, $tglSelesai) {
                        $q->where('tgl_mulai', '<=', $tglMulai)
                            ->where('tgl_selesai', '>=', $tglSelesai);
                    });
            })
            ->where(function($query) use ($jamMulai, $jamSelesai) {
                $query->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            });

        $jadwalBentrok = $jadwalBentrok->exists();

        return $jadwalBentrok;
    }

    public function getDataJadwal($idRuangan){
        $data = JadwalRuangan::with('ruangan')->whereIn('id_ruangan', $idRuangan)->get();

        return $data;
    }
}
