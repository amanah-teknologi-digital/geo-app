<?php

namespace App\Http\Repositories;

use App\Models\Files;
use App\Models\Pengumuman;
use App\Models\User;
use Ramsey\Uuid\Nonstandard\Uuid;

class ManajemenUserRepository
{
    public function getDataUser($idUser){
        $data = User::with(['aksesuser'])->orderBy('created_at', 'DESC');

        if (!empty($idUser)) {
            $data = $data->where('id', $idUser)->first();
        }

        return $data;
    }

    public function tambahPengumuman($request, $id_file){
        $id_pengumuman = strtoupper(Uuid::uuid4()->toString());

        Pengumuman::create([
            'id_pengumuman' => $id_pengumuman,
            'judul' => $request->judul,
            'data' => $request->editor_pengumuman,
            'gambar_header' => $id_file,
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function updatePengumuman($request){
        $id_pengumuman = $request->id_pengumuman;

        $dataPengumuman = Pengumuman::find($id_pengumuman);
        $dataPengumuman->judul = $request->judul;
        $dataPengumuman->data = $request->editor_pengumuman;
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
