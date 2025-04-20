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

        $timestamp = [];
        foreach ($data as $key => $value) {
            $timestampMs = $value->created_at->copy()->startOfDay()->valueOf();
            if (array_key_exists($timestampMs, $timestamp)) {
                $timestamp[$timestampMs]['total_pengajuan'] += 1;
            } else {
                $timestamp[$timestampMs] = [
                    'total_pengajuan' => 1,
                    'total_disetujui' => 0,
                    'total_ditolak' => 0,
                ];
            }

            $createdAtDisetujui = optional($value->persetujuan->first(function ($item) {
                return $item->id_statuspersetujuan == 1 && $item->id_akses == 2;
            }))->created_at;

            $createdAtDitolak = optional($value->persetujuan->first(function ($item) {
                return $item->id_statuspersetujuan == 3;
            }))->created_at;

            if ($createdAtDisetujui){
                $tmpCreatedAtDisetujui = $createdAtDisetujui->copy()->startOfDay()->valueOf();
                if (array_key_exists($tmpCreatedAtDisetujui, $timestamp)) {
                    $timestamp[$tmpCreatedAtDisetujui]['total_disetujui'] += 1;
                } else {
                    $timestamp[$tmpCreatedAtDisetujui] = [
                        'total_pengajuan' => 0,
                        'total_disetujui' => 1,
                        'total_ditolak' => 0,
                    ];
                }
            }

            if ($createdAtDitolak){
                $tmpCreatedAtDitolak = $createdAtDitolak->copy()->startOfDay()->valueOf();
                if (array_key_exists($tmpCreatedAtDitolak, $timestamp)) {
                    $timestamp[$tmpCreatedAtDitolak]['total_ditolak'] += 1;
                } else {
                    $timestamp[$tmpCreatedAtDitolak] = [
                        'total_pengajuan' => 0,
                        'total_disetujui' => 0,
                        'total_ditolak' => 1,
                    ];
                }
            }
        }

        ksort($timestamp);
        $listTanggal = array_keys($timestamp);
        $listPengajuan =  array_column($timestamp, 'total_pengajuan');
        $listDisetujui =  array_column($timestamp, 'total_disetujui');
        $listDitolak =  array_column($timestamp, 'total_ditolak');

        $data = [
            'listTanggal' => $listTanggal,
            'listPengajuan' => $listPengajuan,
            'listDisetujui' => $listDisetujui,
            'listDitolak' => $listDitolak,
        ];
        return $data;
    }
}
