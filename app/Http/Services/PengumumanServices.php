<?php

namespace App\Http\Services;

use App\Http\Repositories\PengumumanRepository;
use App\Models\Files;
use Exception;
use Illuminate\Support\Facades\Log;

class PengumumanServices
{
    private $repository;
    public function __construct(PengumumanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDataPengumuman(){
        $data = $this->repository->getDataPengumuman();

        return $data;
    }

    public function updatePengaturan($request){
        try {
            $this->repository->updatePengaturan($request);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function createOrUpdateFile($file, $id_file, $jenis_file){
        try {
            $fileName = $file->getClientOriginalName();
            $fileMime = $file->getClientMimeType();
            $fileExt = $file->getClientOriginalExtension();
            $newFileName = $id_file.'.'.$fileExt;
            $fileSize = $file->getSize();
            $filePath = $file->storeAs('sop', $newFileName, 'public');

            //save file data ke database
            $this->repository->createOrUpdateFile($id_file, $fileName, $filePath, $fileMime, $fileExt, $fileSize, $jenis_file);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

}
