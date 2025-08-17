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

    public function getUserPenyetujuSurat($search, $idJenisSurat){
        $usedUserIds = PihakPenyetujuSurat::where('id_jenissurat', $idJenisSurat)->pluck('id_penyetuju');

        $availableUsers = User::whereNotIn('id', $usedUserIds)->when($search, fn($q) => $q->where('name', 'like', "%$search%"))->where('email_verified_at', '!=', null)
            ->limit(10)
            ->get(['id', 'name']);

        return $availableUsers;
    }

    public function getUserPenyetujuSuratUpdate($search, $idJenisSurat, $idPihakPenyetuju){
        $usedUserIds = PihakPenyetujuSurat::where('id_jenissurat', $idJenisSurat)->where('id_pihakpenyetuju', '!=', $idPihakPenyetuju)->pluck('id_penyetuju');

        $availableUsers = User::whereNotIn('id', $usedUserIds);

        $availableUsers = $availableUsers->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->limit(10)
            ->get(['id', 'name']);


        return $availableUsers;
    }

    public function tambahJenisSurat($request, $idJenisSurat)
    {
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

    public function tambahPenyetujuSurat($request, $idPihakPenyetuju){
        PihakPenyetujuSurat::create([
            'id_pihakpenyetuju' => $idPihakPenyetuju,
            'id_jenissurat' => $request->id_jenissurat,
            'nama' => $request->nama_persetujuan,
            'id_penyetuju' => $request->user_penyetuju,
            'urutan' => 2,
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function updatePenyetujuSurat($request, $idPihakPenyetuju){
        $pihakPenyetujuSurat = PihakPenyetujuSurat::find($idPihakPenyetuju);

        if ($pihakPenyetujuSurat) {
            $pihakPenyetujuSurat->nama = $request->nama_persetujuan;
            $pihakPenyetujuSurat->id_penyetuju = $request->user_penyetuju;
            $pihakPenyetujuSurat->updated_at = now();
            $pihakPenyetujuSurat->updater = auth()->user()->id;
            $pihakPenyetujuSurat->save();
        }
    }

    public function hapusJenisSurat($idJenisSurat){
        $pihakPenyetuju = PihakPenyetujuSurat::where('id_jenissurat', $idJenisSurat);
        if ($pihakPenyetuju) {
            $pihakPenyetuju->delete();
        }

        $JenisSurat = JenisSurat::find($idJenisSurat);
        if ($JenisSurat) {
            $JenisSurat->delete();
        }
    }

    public function hapusPihakPenyetuju($idPihakPenyetuju){
        $pihakPenyetuju = PihakPenyetujuSurat::find($idPihakPenyetuju);
        if ($pihakPenyetuju) {
            $pihakPenyetuju->delete();
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
