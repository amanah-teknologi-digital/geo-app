<?php

namespace App\Http\Services;

use App\Http\Repositories\PengumumanRepository;
use App\Models\Files;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PengumumanServices
{
    private $repository;
    public function __construct(PengumumanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDataPengumuman($id_pengumuman = null){
        $data = $this->repository->getDataPengumuman($id_pengumuman);

        return $data;
    }

    public function tambahPengumuman($request, $id_file){
        try {
            $this->repository->tambahPengumuman($request, $id_file);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function updatePengumuman($request){
        try {
            $this->repository->updatePengumuman($request);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tambahFile($file, $id_file){
        try {
            $fileName = $file->getClientOriginalName();
            $fileMime = $file->getClientMimeType();
            $fileExt = $file->getClientOriginalExtension();
            $newFileName = $id_file.'.'.$fileExt;
            $fileSize = $file->getSize();
            $fileContents = file_get_contents($file->getRealPath());
            $encryptedFileContents = Crypt::encrypt($fileContents);
            $filePath = 'pengumuman/' . $newFileName;
            Storage::disk('public')->put($filePath, $encryptedFileContents);

            //save file data ke database
            $this->repository->createOrUpdateFile($id_file, $fileName, $filePath, $fileMime, $fileExt, $fileSize);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function hapusFile($id_file, $location){
        try {
            Storage::disk('public')->delete($location);

            //hapus file data di database
            $this->repository->hapusFile($id_file);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function hapusPengumuman($id_pengumuman){
        try {
            $this->repository->hapusPengumuman($id_pengumuman);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function postingPengumuman($id_pengumuman){
        try {
            $this->repository->postingPengumuman($id_pengumuman);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function batalPostingPengumuman($id_pengumuman){
        try {
            $this->repository->batalPostingPengumuman($id_pengumuman);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
