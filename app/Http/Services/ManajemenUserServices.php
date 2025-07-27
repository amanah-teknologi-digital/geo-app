<?php

namespace App\Http\Services;

use App\Http\Repositories\ManajemenUserRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class ManajemenUserServices
{
    private $repository;
    public function __construct(ManajemenUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDataUser($idUser = null){
        $data = $this->repository->getDataUser($idUser);

        return $data;
    }

    public function getListAkses($listAkses){
        $data = $this->repository->getListAkses($listAkses);

        return $data;
    }

    public function tambahAksesUser($idAkses, $idUser){
        try {
            $this->repository->tambahAksesUser($idAkses, $idUser);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function setDefaultAkses($idAkses, $idUser){
        try {
            $this->repository->setDefaultAkses($idAkses, $idUser);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function hapusAkses($idAkses, $idUser)
    {
        try {
            $this->repository->hapusAkses($idAkses, $idUser);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
