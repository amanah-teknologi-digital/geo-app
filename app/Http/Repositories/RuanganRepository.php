<?php

namespace App\Http\Repositories;

use App\Models\JenisSurat;
use App\Models\Ruangan;

class RuanganRepository
{
    public function getDataRuangan($idRuangan){
        $data = Ruangan::with(['pihakupdater','gambar'])->orderBy('created_at');

        if (!empty($idRuangan)) {
            $data = $data->where('id_ruangan', $idRuangan)->first();
        }else{
            $data = $data->get();
        }

        return $data;
    }

    public function tambahJenisSurat($request, $idJenisSurat){
        JenisSurat::create([
            'id_jenissurat' => $idJenisSurat,
            'nama' => $request->nama_jenis,
            'default_form' => $request->editor_quil,
            'is_aktif' => 1,
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function updateJenisSurat($request){
        $idJenisSurat = $request->id_jenissurat;

        $dataPengumuman = JenisSurat::find($idJenisSurat);
        $dataPengumuman->nama = $request->nama_jenis;
        $dataPengumuman->default_form = $request->editor_quil;
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
}
