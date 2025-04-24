<?php

namespace App\Http\Repositories;

use App\Models\Files;
use Illuminate\Support\Facades\DB;

class ManajemenFileTinyMceRepository
{
    public function createOrUpdateFile($idFile, $fileName, $filePath, $fileMime, $fileExt, $fileSize, $idUser){
        $file = Files::find($idFile);

        if ($file) {
            $file->file_name = $fileName;
            $file->location = $filePath;
            $file->mime = $fileMime;
            $file->ext = $fileExt;
            $file->file_size = $fileSize;
            $file->is_private = 0;
            $file->updated_at = now();
            $file->updater = $idUser;
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
                'updater' => $idUser
            ]);
        }
    }
}
