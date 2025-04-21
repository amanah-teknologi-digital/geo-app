<?php

namespace App\Http\Repositories;

use App\Models\Files;
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

    public function tambahRuangan($request, $idRuangan, $idFileGambar){
        Ruangan::create([
            'id_ruangan' => $idRuangan,
            'kode_ruangan' => $request->kode_ruangan,
            'nama' => $request->nama_ruangan,
            'deskripsi' => $request->deskripsi,
            'keterangan' => $request->keterangan,
            'kapasitas' => $request->kapasitas,
            'lantai' => $request->lantai,
            'gambar_file' => $idFileGambar,
            'is_aktif' => 1,
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function createOrUpdateFile($idFile, $fileName, $filePath, $fileMime, $fileExt, $fileSize){
        $file = Files::find($idFile);

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
                'id_file' => $idFile,
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

    public function updateRuangan($request, $idRuangan){
        $dataRuangan = Ruangan::find($idRuangan);
        $dataRuangan->kode_ruangan = $request->kode_ruangan;
        $dataRuangan->nama = $request->nama_ruangan;
        $dataRuangan->deskripsi = $request->deskripsi;
        $dataRuangan->keterangan = $request->keterangan;
        $dataRuangan->kapasitas = $request->kapasitas;
        $dataRuangan->lantai = $request->lantai;
        $dataRuangan->is_aktif = 1;
        $dataRuangan->updated_at = now();
        $dataRuangan->updater = auth()->user()->id;
        $dataRuangan->save();
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
