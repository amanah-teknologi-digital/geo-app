<?php

namespace App\Http\Services;

use App\Http\Repositories\PengaturanRepository;
use App\Models\Files;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PengaturanServices
{
    private $repository;
    public function __construct(PengaturanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDataPengaturan(){
        $data = $this->repository->getDataPengaturan();

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
            $fileContents = file_get_contents($file->getRealPath());
            $encryptedFileContents = Crypt::encrypt($fileContents);
            $filePath = 'sop/' . $newFileName;
            Storage::disk('public')->put($filePath, $encryptedFileContents);

            //save file data ke database
            $this->repository->createOrUpdateFile($id_file, $fileName, $filePath, $fileMime, $fileExt, $fileSize, $jenis_file);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

}
