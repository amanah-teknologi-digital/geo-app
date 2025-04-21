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

    public function tambahJenisSurat($request, $idJenisSurat){
        try {
            $this->repository->tambahJenisSurat($request, $idJenisSurat);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function updateJenisSurat($request){
        try {
            $this->repository->updateJenisSurat($request);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function hapusJenisSurat($idJenisSurat){
        try {
            $this->repository->hapusJenisSurat($idJenisSurat);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function aktifkanJenisSurat($idJenisSurat){
        try {
            $this->repository->aktifkanJenisSurat($idJenisSurat);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function nonAktifkanJenisSurat($idJenisSurat){
        try {
            $this->repository->nonAktifkanJenisSurat($idJenisSurat);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
