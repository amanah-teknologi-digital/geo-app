<?php

namespace App\Http\Repositories;

use App\Models\Files;
use App\Models\JenisSurat;
use App\Models\PengajuanGeoLetter;
use App\Models\Pengumuman;
use Ramsey\Uuid\Nonstandard\Uuid;

class PengajuanGeoLetterRepository
{
    public function getDataPengajuan($id_pengajuan){
        $data = PengajuanGeoLetter::select('id_pengajuan', 'pengaju', 'id_statuspengajuan', 'id_jenissurat', 'nama_pengaju', 'no_hp', 'email', 'kartu_id', 'created_at', 'updated_at', 'updater', 'keterangan', 'data_form')
            ->with(['pihakupdater','jenis_surat','statuspengajuan'])->orderBy('created_at', 'desc');

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
        PengajuanGeoLetter::create([
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

    public function updatePengumuman($request){
        $id_pengumuman = $request->id_pengumuman;

        $dataPengumuman = Pengumuman::find($id_pengumuman);
        $dataPengumuman->judul = $request->judul;
        $dataPengumuman->data = $request->editor_quil;
        $dataPengumuman->updated_at = now();
        $dataPengumuman->updater = auth()->user()->id;
        $dataPengumuman->save();
    }

    public function createOrUpdateFile($id_file, $fileName, $filePath, $fileMime, $fileExt, $fileSize){
        $file = Files::find($id_file);

        if ($file) {
            $file->file_name = $fileName;
            $file->location = $filePath;
            $file->mime = $fileMime;
            $file->ext = $fileExt;
            $file->file_size = $fileSize;
            $file->is_private = 0;
            $file->updated_at = now();
            $file->updater = auth()->user()->id;
            $file->save();
        } else {
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

    public function hapusFile($id_file){
        $file = Files::find($id_file);
        if ($file) {
            $file->delete();
        }
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
