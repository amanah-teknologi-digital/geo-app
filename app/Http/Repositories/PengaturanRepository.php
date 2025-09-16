<?php

namespace App\Http\Repositories;

use App\Models\Files;
use App\Models\Pengaturan;

class PengaturanRepository
{
    public function getDataPengaturan(){
        $data = Pengaturan::with(['files_geoletter', 'files_georoom', 'files_geofacility'])->first();

        return $data;
    }

    public function updatePengaturan($request){
        $pengaturan = Pengaturan::first(); // Ambil data pertama di tabel Pengaturan

        if ($pengaturan) {
            $data = [
                'alamat' => $request->alamat,
                'admin_geoletter' => $request->admin_geoletter,
                'admin_ruang' => $request->admin_ruang,
                'admin_peralatan' => $request->admin_peralatan,
                'link_yt' => $request->link_yt,
                'link_fb' => $request->link_fb,
                'link_ig' => $request->link_ig,
                'link_linkedin' => $request->link_linkedin,
                'pihak_penyetuju' => $request->pihak_penyetuju,
                'nama_penyetuju' => $request->nama_penyetuju,
                'nomor_surat' => $request->nomor_surat,
                'updater' => auth()->user()->id,
            ];

            $pengaturan->update($data);
        } else {
            Pengaturan::create([
                'alamat' => $request->alamat,
                'admin_geoletter' => $request->admin_geoletter,
                'admin_ruang' => $request->admin_ruang,
                'admin_peralatan' => $request->admin_peralatan,
                'link_yt' => $request->link_yt,
                'link_fb' => $request->link_fb,
                'link_ig' => $request->link_ig,
                'link_linkedin' => $request->link_linkedin,
                'pihak_penyetuju' => $request->pihak_penyetuju,
                'nama_penyetuju' => $request->nama_penyetuju,
                'nomor_surat' => $request->nomor_surat,
                'updater' => auth()->user()->id,
                'updated_at' => now()
            ]);
        }
    }

    public function createOrUpdateFile($id_file, $fileName, $filePath, $fileMime, $fileExt, $fileSize, $jenis_file){
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

            $dataPengaturan = Pengaturan::first();
            $dataPengaturan->$jenis_file = $id_file;
            $dataPengaturan->save();
        }
    }
}
