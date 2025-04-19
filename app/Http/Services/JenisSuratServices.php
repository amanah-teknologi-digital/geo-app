<?php

namespace App\Http\Services;

use App\Http\Repositories\JenisSuratRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
