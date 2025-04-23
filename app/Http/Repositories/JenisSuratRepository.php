<?php

namespace App\Http\Repositories;

use App\Models\JenisSurat;

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
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function updateJenisSurat($request){
        $idJenisSurat = $request->id_jenissurat;

        $dataPengumuman = JenisSurat::find($idJenisSurat);
        $dataPengumuman->nama = $request->nama_jenis;
        $dataPengumuman->default_form = $request->editor;
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
