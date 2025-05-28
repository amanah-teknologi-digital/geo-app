<?php

namespace App\Http\Services;

use App\Http\Repositories\JenisSuratRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Nonstandard\Uuid;

class JenisSuratServices
{
    private $repository;
    public function __construct(JenisSuratRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDataJenisSurat($idJenisSurat = null){
        $data = $this->repository->getDataJenisSurat($idJenisSurat);

        return $data;
    }

    public function getPihakPenyetujuSurat($idJenisSurat){
        $data = $this->repository->getPihakPenyetujuSurat($idJenisSurat);

        return $data;
    }

    public function getUserPenyetujuSurat($search, $idJenisSurat){
        $data = $this->repository->getUserPenyetujuSurat($search, $idJenisSurat);

        return $data;
    }

    public function tambahJenisSurat($request, $idJenisSurat){
        try {
            $idPihakPenyetuju = strtoupper(Uuid::uuid4()->toString());

            $this->repository->tambahJenisSurat($request, $idJenisSurat);
            $this->repository->tambahdDefaultPenyetuju($idPihakPenyetuju, $idJenisSurat);
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

    public function hapusPihakPenyetujuSurat($idPihakPenyetuju){
        try {
            $this->repository->hapusPihakPenyetuju($idPihakPenyetuju);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tambahPenyetujuSurat($request){
        try {
            $idPihakPenyetuju = strtoupper(Uuid::uuid4()->toString());

            $this->repository->tambahPenyetujuSurat($request, $idPihakPenyetuju);
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
