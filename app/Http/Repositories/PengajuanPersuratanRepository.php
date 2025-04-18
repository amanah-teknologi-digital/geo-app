<?php

namespace App\Http\Repositories;

use App\Models\Files;
use App\Models\JenisSurat;
use App\Models\PengajuanPersuratan;
use App\Models\Pengumuman;
use App\Models\PersetujuanPersuratan;
use App\Models\User;
use Ramsey\Uuid\Nonstandard\Uuid;

class PengajuanPersuratanRepository
{
    public function getDataPengajuan($id_pengajuan, $id_akses){
        $data = PengajuanPersuratan::select('id_pengajuan', 'pengaju', 'id_statuspengajuan', 'id_jenissurat', 'nama_pengaju', 'no_hp', 'email', 'kartu_id', 'created_at', 'updated_at', 'updater', 'keterangan', 'data_form')
            ->with(['pihakpengaju','pihakupdater','jenis_surat','statuspengajuan','persetujuan']);

        $id_pengguna = auth()->user()->id;
        if ($id_akses == 8){ //pengguna
            $data = $data->where('pengaju', $id_pengguna)
                ->orderByRaw('CASE
                    WHEN id_statuspengajuan = 0 THEN 0
                    WHEN id_statuspengajuan = 4 THEN 1
                    ELSE 2
                    END')
                ->orderBy('id_statuspengajuan');
        }

        if ($id_akses == 2){ // admin geo harus status tidak draft
            $data = $data->where('id_statuspengajuan', '!=', 0)
                ->orderByRaw('IF(EXISTS(SELECT 1 FROM persetujuan_surat WHERE persetujuan_surat.id_pengajuan = pengajuan_surat.id_pengajuan AND persetujuan_surat.id_akses = 2), 1, 0)') // Urutkan yang tidak ada id_akses = 2 ke atas
                ->orderByRaw('CASE
                    WHEN id_statuspengajuan = 5 THEN 0
                    ELSE 1
                    END')
                ->orderBy('id_statuspengajuan');
        }

        if ($id_akses == 1){ //super admin
            $data = $data
                ->orderByRaw('IF(EXISTS(SELECT 1 FROM persetujuan_surat WHERE persetujuan_surat.id_pengajuan = pengajuan_surat.id_pengajuan AND persetujuan_surat.id_akses = 2), 1, 0)') // Urutkan yang tidak ada id_akses = 2 ke atas
                ->orderByRaw('CASE
                    WHEN id_statuspengajuan = 0 THEN 0
                    WHEN id_statuspengajuan = 4 THEN 1
                    WHEN id_statuspengajuan = 5 THEN 2
                    ELSE 3
                    END') // Urutkan dengan id_statuspengajuan 0, 4, 5
                ->orderBy('id_statuspengajuan');
        }

        $data = $data->orderBy('created_at', 'desc');

        if (!empty($id_pengajuan)) {
            $data = $data->where('id_pengajuan', $id_pengajuan)->first();
        }

        return $data;
    }

    public function getJenisSurat($id_jenissurat){
        $data = JenisSurat::select('id_jenissurat', 'nama', 'default_form', 'created_at', 'updated_at')->orderBy('created_at');
        if (!empty($id_jenissurat)) {
            $data = $data->where('id_jenissurat', $id_jenissurat)->first();
        }else{
            $data = $data->get();
        }

        return $data;
    }

    public function tambahPengajuan($request, $id_pengajuan){
        PengajuanPersuratan::create([
            'id_pengajuan' => $id_pengajuan,
            'pengaju' => auth()->user()->id,
            'id_statuspengajuan' => 0, //draft
            'id_jenissurat' => $request->jenis_surat,
            'nama_pengaju' => auth()->user()->name,
            'no_hp' => auth()->user()->no_hp,
            'email' => auth()->user()->email,
            'kartu_id' => auth()->user()->kartu_id,
            'keterangan' => $request->keterangan,
            'data_form' => $request->editor_quil,
            'created_at' => now(),
            'updater' => auth()->user()->id
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
        $dataPengajuan->id_jenissurat = $request->jenis_surat;
        $dataPengajuan->keterangan = $request->keterangan;
        $dataPengajuan->data_form = $request->editor_quil;
        $dataPengajuan->updated_at = now();
        $dataPengajuan->updater = auth()->user()->id;
        $dataPengajuan->save();
    }

    public function updateDataPemohon($id_pengajuan){
        $dataPengajuan = $this->getDataPengajuan($id_pengajuan, auth()->user()->id_akses);
        $dataUser = User::find($dataPengajuan->pengaju);

        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->nama_pengaju = $dataUser->name;
        $dataPengajuan->kartu_id = $dataUser->kartu_id;
        $dataPengajuan->no_hp = $dataUser->no_hp;
        $dataPengajuan->email = $dataUser->email;
        $dataPengajuan->save();
    }

    public function hapusPengajuan($id_pengajuan){
        $pengajuan = PengajuanPersuratan::where('id_pengajuan', $id_pengajuan)->where('id_statuspengajuan', 0)->first();
        if ($pengajuan) {
            $pengajuan->delete();
        }
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
}
