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

    public function getDataNotifSurat($idAkses){
        $data = $this->repository->getDataNotifSurat($idAkses);

        $isNotif = false; $isNotifSurat = false; $jmlNotif = []; $jmlNotifAjukan = 0; $jmlNotifVerifikasi = 0; $jmlNotifRevisi = 0;
        foreach ($data as $key => $value) {
            if ($idAkses == 1){ //super admin
                if ($value->id_statuspengajuan == 0){
                    $isNotif = true;
                    $isNotifSurat = true;

                    if (empty($jmlNotif) or !in_array('ajuansurat', $jmlNotif)){
                        $jmlNotif[] = 'ajuansurat';
                    }

                    $jmlNotifAjukan += 1;
                }

                $idPersetujuanAdmin = optional($value->persetujuan->first(function ($item) {
                    return $item->id_statuspersetujuan == 1 && $item->id_akses == 2;
                }))->id_persetujuan;

                if ($value->id_statuspengajuan == 5 || $value->id_statuspengajuan == 2 && empty($idPersetujuanAdmin)){
                    $isNotif = true;
                    $isNotifSurat = true;

                    if (empty($jmlNotif) or !in_array('verifikasisurat', $jmlNotif)){
                        $jmlNotif[] = 'verifikasisurat';
                    }

                    $jmlNotifVerifikasi += 1;
                }

                if ($value->id_statuspengajuan == 4){
                    $isNotif = true;
                    $isNotifSurat = true;

                    if (empty($jmlNotif) or !in_array('revisisurat', $jmlNotif)){
                        $jmlNotif[] = 'revisisurat';
                    }

                    $jmlNotifRevisi += 1;
                }
            }elseif ($idAkses == 2){ //admin
                $idPersetujuanAdmin = optional($value->persetujuan->first(function ($item) {
                    return $item->id_statuspersetujuan == 1 && $item->id_akses == 2;
                }))->id_persetujuan;

                if ($value->id_statuspengajuan == 5 || $value->id_statuspengajuan == 2 && empty($idPersetujuanAdmin)){
                    $isNotif = true;
                    $isNotifSurat = true;

                    if (empty($jmlNotif) or !in_array('verifikasisurat', $jmlNotif)){
                        $jmlNotif[] = 'verifikasisurat';
                    }

                    $jmlNotifVerifikasi += 1;
                }
            }elseif ($idAkses == 8){ //pengguna
                if ($value->id_statuspengajuan == 0){
                    $isNotif = true;
                    $isNotifSurat = true;

                    if (empty($jmlNotif) or !in_array('ajuansurat', $jmlNotif)){
                        $jmlNotif[] = 'ajuansurat';
                    }

                    $jmlNotifAjukan += 1;
                }

                if ($value->id_statuspengajuan == 4){
                    $isNotif = true;
                    $isNotifSurat = true;

                    if (empty($jmlNotif) or !in_array('revisisurat', $jmlNotif)){
                        $jmlNotif[] = 'revisisurat';
                    }

                    $jmlNotifRevisi += 1;
                }
            }
        }

        $data = [
            'isNotif' => $isNotif,
            'jmlNotif' => $jmlNotif,
            'isNotifSurat' => $isNotifSurat,
            'jmlNotifAjukan' => $jmlNotifAjukan,
            'jmlNotifVerifikasi' => $jmlNotifVerifikasi,
            'jmlNotifRevisi' => $jmlNotifRevisi,
        ];

        return $data;
    }
}
