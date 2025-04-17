<?php

namespace App\Http\Repositories;

use App\Models\Files;
use App\Models\JenisSurat;
use App\Models\PengajuanPersuratan;
use App\Models\Pengumuman;
use App\Models\PersetujuanPersuratan;
use Ramsey\Uuid\Nonstandard\Uuid;

class PengajuanPersuratanRepository
{
    public function getDataPengajuan($id_pengajuan){
        $data = PengajuanPersuratan::select('id_pengajuan', 'pengaju', 'id_statuspengajuan', 'id_jenissurat', 'nama_pengaju', 'no_hp', 'email', 'kartu_id', 'created_at', 'updated_at', 'updater', 'keterangan', 'data_form')
            ->with(['pihakupdater','jenis_surat','statuspengajuan','persetujuan'])->orderBy('created_at', 'desc');

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

        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->nama_pengaju = auth()->user()->name;
        $dataPengajuan->kartu_id = auth()->user()->kartu_id;
        $dataPengajuan->no_hp = auth()->user()->no_hp;
        $dataPengajuan->email = auth()->user()->email;
        $dataPengajuan->id_jenissurat = $request->jenis_surat;
        $dataPengajuan->keterangan = $request->keterangan;
        $dataPengajuan->data_form = $request->editor_quil;
        $dataPengajuan->updated_at = now();
        $dataPengajuan->updater = auth()->user()->id;
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

    public function tambahPersetujuan($id_pengajuan, $id_akses, $id_statuspersetujuan, $keterangan = null){
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
