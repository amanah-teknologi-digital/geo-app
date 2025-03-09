<?php

namespace App\Http\Repositories;

use App\Models\Files;
use App\Models\Pengumuman;
use Ramsey\Uuid\Nonstandard\Uuid;

class PengumumanRepository
{
    public function getDataPengumuman($id_pengumuman){
        $data = Pengumuman::select('id_pengumuman', 'judul', 'data', 'gambar_header', 'created_at', 'updated_at', 'updater', 'is_posting', 'postinger')
            ->with(['user','file_pengumuman','postinger'])->orderBy('created_at', 'DESC');

        if (!empty($id_pengumuman)) {
            $data = $data->where('id_pengumuman', $id_pengumuman)->first();
        }

        return $data;
    }

    public function tambahPengumuman($request, $id_file){
        $id_pengumuman = strtoupper(Uuid::uuid4()->toString());

        Pengumuman::create([
            'id_pengumuman' => $id_pengumuman,
            'judul' => $request->judul,
            'data' => $request->editor_quil,
            'gambar_header' => $id_file,
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
}
