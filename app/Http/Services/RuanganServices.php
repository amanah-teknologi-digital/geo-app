<?php

namespace App\Http\Services;

use App\Http\Repositories\RuanganRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class RuanganServices
{
    private $repository;
    public function __construct(RuanganRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDataRuangan($idRuangan = null){
        $data = $this->repository->getDataRuangan($idRuangan);

        return $data;
    }

    public function getJenisRuangan(){
        $data = $this->repository->getJenisRuangan();

        return $data;
    }

    public function checkAksesTambah($idAkses){
        if (in_array($idAkses,[1,3])){ //cuma bisa super admin & admin
            $isTambah = true;
        }else{
            $isTambah = false;
        }

        return $isTambah;
    }

    public function checkAksesEdit($idAkses){
        if (in_array($idAkses,[1,3])){ //cuma bisa super admin & admin
            $isEdit = true;
        }else{
            $isEdit = false;
        }

        return $isEdit;
    }

    public function tambahFile($file, $idFile){
        try {
            $fileName = $file->getClientOriginalName();
            $fileMime = $file->getClientMimeType();
            $fileExt = $file->getClientOriginalExtension();
            $newFileName = $idFile.'.'.$fileExt;
            $fileSize = $file->getSize();
            $filePath = $file->storeAs('ruangan', $newFileName, 'public');

            //save file data ke database
            $this->repository->createOrUpdateFile($idFile, $fileName, $filePath, $fileMime, $fileExt, $fileSize);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tambahRuangan($request, $idRuangan, $idFileGambar){
        try {
            $request->fasilitas = $this->getDataJsonFasilitas($request->fasilitas);

            $this->repository->tambahRuangan($request, $idRuangan, $idFileGambar);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getDataJsonFasilitas($dataFasilitas){
        $configFasilitas = config('listfasilitas', []);
        $flatFasilitas = [];

        // Flatten semua fasilitas jadi key => full data
        foreach ($configFasilitas as $kategori => $items) {
            foreach ($items as $item) {
                $flatFasilitas[$item['id']] = $item;
            }
        }

        // Ambil fasilitas yang dipilih dan buat array final
        $result = [];

        foreach ($dataFasilitas as $id) {
            if (isset($flatFasilitas[$id])) {
                $result[] = [
                    'id'   => $id,
                    'text' => $flatFasilitas[$id]['text'],
                    'icon' => $flatFasilitas[$id]['icon'],
                ];
            }
        }

        // Simpan sebagai JSON
        $jsonFasilitas = json_encode($result, JSON_PRETTY_PRINT);

        return $jsonFasilitas;
    }

    public function updateRuangan($request, $idRuangan){
        try {
            $request->fasilitas = $this->getDataJsonFasilitas($request->fasilitas);

            $this->repository->updateRuangan($request, $idRuangan);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getDataJadwal($idRuangan){
        $data = $this->repository->getDataJadwal($idRuangan);

        return $data;
    }
}
