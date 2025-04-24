<?php

namespace App\Http\Services;

use App\Http\Repositories\ManajemenFileTinyMceRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class ManajemenFileTinyMceServices
{
    private $repository;
    public function __construct(ManajemenFileTinyMceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function tambahFile($file, $id_file, $idUser){
        try {
            $fileName = $file->getClientOriginalName();
            $fileMime = $file->getClientMimeType();
            $fileExt = $file->getClientOriginalExtension();
            $newFileName = $id_file.'.'.$fileExt;
            $fileSize = $file->getSize();
            $filePath = $file->storeAs('temp_tinymce', $newFileName, 'public');

            //save file data ke database
            $this->repository->createOrUpdateFile($id_file, $fileName, $filePath, $fileMime, $fileExt, $fileSize, $idUser);

            return $filePath;
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
