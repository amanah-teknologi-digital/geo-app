<?php

namespace App\Http\Services;

use App\Http\Repositories\PengaturanRepository;
use Exception;
use Illuminate\Support\Facades\Log;

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

}
