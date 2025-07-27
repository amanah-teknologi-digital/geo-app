<?php

namespace App\Http\Services;

use App\Http\Repositories\DashboardRepository;
use App\Http\Repositories\PengajuanPersuratanRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardServices
{
    private $repository;
    private $servicePengajuanPersuratan;
    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
        $repositorySurat = new PengajuanPersuratanRepository();
        $this->servicePengajuanPersuratan = new PengajuanPersuratanServices($repositorySurat);
    }

    public function getDataTotalPersuratan($tahun, $idUser = null)
    {
        $data = $this->repository->getDataTotalPersuratan($tahun, $idUser);

        return $data;
    }

    public function getDataStatistikPersuratan($tahun, $idUser = null)
    {
        $data = $this->repository->getDataStatistikPersuratan($tahun, $idUser);

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

    public function getDataNotifSurat($idAkses, $namaLayananPersuratan){
        $data = $this->repository->getDataNotifSurat($idAkses);

        $isNotif = false; $isNotifSurat = false; $jmlNotif = []; $jmlNotifAjukan = 0; $jmlNotifVerifikasi = 0; $jmlNotifRevisi = 0;
        foreach ($data as $key => $value) {
            $idPengajuan = $value->pengajuan_id;
            $dataVerifikasi = $this->servicePengajuanPersuratan->getStatusVerifikasi($idPengajuan, $namaLayananPersuratan, $value);
            $mustApprove = $dataVerifikasi['must_aprove'];
            $message = $dataVerifikasi['message'];

            if ($mustApprove == 'AJUKAN'){
                $isNotif = true;
                $isNotifSurat = true;

                if (empty($jmlNotif) or !in_array('ajuansurat', $jmlNotif)){
                    $jmlNotif[] = 'ajuansurat';
                }

                $jmlNotifAjukan += 1;
            }

            if ($mustApprove == 'VERIFIKASI'){
                $isNotif = true;
                $isNotifSurat = true;

                if (empty($jmlNotif) or !in_array('verifikasisurat', $jmlNotif)){
                    $jmlNotif[] = 'verifikasisurat';
                }

                $jmlNotifVerifikasi += 1;
            }

            if ($mustApprove == 'SUDAH DIREVISI'){
                $isNotif = true;
                $isNotifSurat = true;

                if (empty($jmlNotif) or !in_array('revisisurat', $jmlNotif)){
                    $jmlNotif[] = 'revisisurat';
                }

                $jmlNotifRevisi += 1;
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
