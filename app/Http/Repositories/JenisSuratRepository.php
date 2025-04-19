<?php

namespace App\Http\Repositories;

use App\Models\Files;
use App\Models\JenisSurat;
use App\Models\Pengumuman;
use Ramsey\Uuid\Nonstandard\Uuid;

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

    public function hapusPengumuman($id_pengumuman){
        $pengumuman = Pengumuman::find($id_pengumuman);
        if ($pengumuman) {
            $pengumuman->delete();
        }
    }

    public function postingPengumuman($id_pengumuman){
        $pengumuman = Pengumuman::find($id_pengumuman);
        if ($pengumuman) {
            $pengumuman->is_posting = 1;
            $pengumuman->tgl_posting = now();
            $pengumuman->postinger = auth()->user()->id;
            $pengumuman->save();
        }
    }

    public function batalPostingPengumuman($id_pengumuman){
        $pengumuman = Pengumuman::find($id_pengumuman);
        if ($pengumuman) {
            $pengumuman->is_posting = 0;
            $pengumuman->tgl_posting = null;
            $pengumuman->postinger = null;
            $pengumuman->save();
        }
    }
}
