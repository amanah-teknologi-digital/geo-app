<?php

namespace App\Http\Services;

use App\Http\Repositories\PengaturanRepository;

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

}
