<?php

namespace App\Http\Services;

use App\Http\Repositories\DashboardRepository;
use App\Http\Repositories\PengajuanPersuratanRepository;
use App\Http\Repositories\PengajuanRuanganRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardServices
{
    private $repository;
    private $servicePengajuanPersuratan;
    private $servicePengajuanRuangan;
    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
        $repositorySurat = new PengajuanPersuratanRepository();
        $repositoryRuangan = new PengajuanRuanganRepository();
        $this->servicePengajuanPersuratan = new PengajuanPersuratanServices($repositorySurat);
        $this->servicePengajuanRuangan = new PengajuanRuanganServices($repositoryRuangan);
    }

    public function getDataTotalPersuratan($tahun, $idUser = null)
    {
        $data = $this->repository->getDataTotalPersuratan($tahun, $idUser);

        return $data;
    }

    public function getDataTotalPengajuanRuangan($tahun, $idUser = null)
    {
        $data = $this->repository->getDataTotalPengajuanRuangan($tahun, $idUser);

        return $data;
    }

    public function getDataTotal($tahun, $idUser){
        $dataSurat = $this->repository->getDataTotalPersuratan($tahun, $idUser);
        $dataRuang = $this->repository->getDataTotalPengajuanRuangan($tahun, $idUser);

        $data = [
            'tahun' => $tahun,
            'total_pengajuan' => $dataSurat->total_pengajuan + $dataRuang->total_pengajuan,
            'disetujui' => $dataSurat->disetujui + $dataRuang->disetujui,
            'ditolak' => $dataRuang->ditolak + $dataSurat->ditolak,
            'on_proses' => $dataSurat->on_proses + $dataRuang->on_proses
        ];

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

    public function getDataStatistikRuangan($tahun, $idUser = null)
    {
        $data = $this->repository->getDataStatistikRuangan($tahun, $idUser);

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
                return $item->id_statuspersetujuan == 1 && $item->id_tahapan == 9;
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

    public function getDataStatistik($tahun, $idUser = null){
        $dataSurat = $this->repository->getDataStatistikPersuratan($tahun, $idUser);
        $dataRuang = $this->repository->getDataStatistikRuangan($tahun, $idUser);

        $timestamp = [];
        foreach ($dataSurat as $key => $value) {
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

        foreach ($dataRuang as $key => $value) {
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
                return $item->id_statuspersetujuan == 1 && $item->id_tahapan == 9;
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

        $isNotif = false; $isNotifSurat = false; $jmlNotif = 0; $jmlNotifAjukan = 0; $jmlNotifVerifikasi = 0; $jmlNotifRevisi = 0;
        foreach ($data as $key => $value) {
            $idPengajuan = $value->pengajuan_id;
            $dataVerifikasi = $this->servicePengajuanPersuratan->getStatusVerifikasi($idPengajuan, $namaLayananPersuratan, $value);
            $mustApprove = $dataVerifikasi['must_aprove'];
            $message = $dataVerifikasi['message'];

            if ($mustApprove == 'AJUKAN'){
                $isNotif = true;
                $isNotifSurat = true;
                $jmlNotif++;
                $jmlNotifAjukan += 1;
            }

            if ($mustApprove == 'VERIFIKASI'){
                $isNotif = true;
                $isNotifSurat = true;
                $jmlNotif++;
                $jmlNotifVerifikasi += 1;
            }

            if ($mustApprove == 'SUDAH DIREVISI'){
                $isNotif = true;
                $isNotifSurat = true;
                $jmlNotif++;
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

    public function getDataNotifRuangan($idAkses, $namaLayananRuang){
        $data = $this->repository->getDataNotifRuangan($idAkses);

        $isNotif = false; $isNotifSurat = false; $jmlNotif = 0; $jmlNotifAjukan = 0; $jmlNotifVerifikasi = 0; $jmlNotifRevisi = 0;
        foreach ($data as $key => $value) {
            $idPengajuan = $value->pengajuan_id;
            $dataVerifikasi = $this->servicePengajuanRuangan->getStatusVerifikasi($idPengajuan, $namaLayananRuang, $value);
            $mustApprove = $dataVerifikasi['must_aprove'];
            $message = $dataVerifikasi['message'];

            if ($mustApprove == 'AJUKAN'){
                $isNotif = true;
                $isNotifSurat = true;
                $jmlNotif++;
                $jmlNotifAjukan += 1;
            }

            if ($mustApprove == 'VERIFIKASI'){
                $isNotif = true;
                $isNotifSurat = true;
                $jmlNotif++;
                $jmlNotifVerifikasi += 1;
            }
        }

        $data = [
            'isNotif' => $isNotif,
            'jmlNotif' => $jmlNotif,
            'isNotifRuangan' => $isNotifSurat,
            'jmlNotifAjukan' => $jmlNotifAjukan,
            'jmlNotifVerifikasi' => $jmlNotifVerifikasi
        ];

        return $data;
    }

    public function getSurveyKepuasan(){
        $data = $this->repository->getSurveyKepuasan();

        return $data;
    }

    public function getSurveyKepuasanRuang(){
        $data = $this->repository->getSurveyKepuasanRuang();

        return $data;
    }
}
