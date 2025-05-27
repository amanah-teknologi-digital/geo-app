<?php

namespace App\Http\Repositories;

use App\Models\Akses;
use App\Models\AksesUser;
use App\Models\JenisSurat;
use App\Models\PihakPenyetujuSurat;
use App\Models\User;

class JenisSuratRepository
{
    public function getDataJenisSurat($idJenisSurat){
        $data = JenisSurat::with(['pihakupdater','pengajuansurat'])->orderBy('created_at');

        if (!empty($idJenisSurat)) {
            $data = $data->where('id_jenissurat', $idJenisSurat)->first();
        }

        return $data;
    }

    public function tambahJenisSurat($request, $idJenisSurat){
        JenisSurat::create([
            'id_jenissurat' => $idJenisSurat,
            'nama' => $request->nama_jenis,
            'default_form' => $request->editor,
            'is_aktif' => 1,
            'is_datapendukung' => $request->has('is_datapendukung') ? 1 : 0,
            'nama_datapendukung' => $request->has('is_datapendukung') ? $request->keterangan_datadukung : null,
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function tambahdDefaultPenyetuju($idPihakPenyetuju, $idJenisSurat){
        $AksesPenyetuju = Akses::where('id_akses', 2)->first();
        PihakPenyetujuSurat::create([
            'id_pihakpenyetuju' => $idPihakPenyetuju,
            'id_jenissurat' => $idJenisSurat,
            'nama' => $AksesPenyetuju->nama,
            'urutan' => 1,
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function updateJenisSurat($request){
        $idJenisSurat = $request->id_jenissurat;

        $dataPengumuman = JenisSurat::find($idJenisSurat);
        $dataPengumuman->nama = $request->nama_jenis;
        $dataPengumuman->default_form = $request->editor;
        $dataPengumuman->is_datapendukung = $request->has('is_datapendukung') ? 1 : 0;
        $dataPengumuman->nama_datapendukung = $request->has('is_datapendukung') ? $request->keterangan_datadukung : null;
        $dataPengumuman->updated_at = now();
        $dataPengumuman->updater = auth()->user()->id;
        $dataPengumuman->save();
    }

    public function hapusJenisSurat($idJenisSurat){
        $JenisSurat = JenisSurat::find($idJenisSurat);
        if ($JenisSurat) {
            $JenisSurat->delete();
        }
    }

    public function aktifkanJenisSurat($idJenisSurat){
        $JenisSurat = JenisSurat::find($idJenisSurat);
        if ($JenisSurat) {
            $JenisSurat->is_aktif = 1;
            $JenisSurat->updated_at = now();
            $JenisSurat->updater = auth()->user()->id;
            $JenisSurat->save();
        }
    }

    public function nonAktifkanJenisSurat($idJenisSurat){
        $JenisSurat = JenisSurat::find($idJenisSurat);
        if ($JenisSurat) {
            $JenisSurat->is_aktif = 0;
            $JenisSurat->updated_at = now();
            $JenisSurat->updater = auth()->user()->id;
            $JenisSurat->save();
        }
    }

    public function getPihakPenyetujuSurat($idJenisSurat){
        $data = PihakPenyetujuSurat::where('id_jenissurat', $idJenisSurat)
            ->with(['userpenyetuju'])
            ->orderBy('urutan')
            ->get();

        return $data;
    }
}
