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

    public function getDataJenisSurat($idJenisSurat = null){
        $data = $this->repository->getDataJenisSurat($idJenisSurat);

        return $data;
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
