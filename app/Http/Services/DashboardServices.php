<?php

namespace App\Http\Services;

use App\Http\Repositories\DashboardRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardServices
{
    private $repository;
    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDataTotalPersuratan($tahun)
    {
        $data = $this->repository->getDataTotalPersuratan($tahun);

        return $data;
    }

    public function getDataStatistikPersuratan($tahun)
    {
        $data = $this->repository->getDataStatistikPersuratan($tahun);

        return $data;
    }
}
