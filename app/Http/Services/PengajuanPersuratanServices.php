<?php

namespace App\Http\Services;

use App\Http\Repositories\PengajuanPersuratanRepository;
use App\Models\Files;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PengajuanPersuratanServices
{
    private $repository;
    public function __construct(PengajuanPersuratanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDataPengajuan($id_pengajuan = null){
        $data = $this->repository->getDataPengajuan($id_pengajuan);

        return $data;
    }

    public function tambahPengajuan($request, $id_pengajuan){
        try {
            $this->repository->tambahPengajuan($request, $id_pengajuan);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getJenisSurat($id_jenissurat = null){
        $data = $this->repository->getJenisSurat($id_jenissurat);

        return $data;
    }

    public function updatePengajuan($request){
        try {
            $this->repository->updatePengajuan($request);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function hapusPengajuan($id_pengajuan){
        try {
            $this->repository->hapusPengajuan($id_pengajuan);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function checkOtoritasPengajuan($id_statuspengajuan){
        $id_akses = auth()->user()->akses->id_akses;

        if (($id_statuspengajuan == 0 OR $id_statuspengajuan == 4) && in_array($id_akses, [1, 8])) { //jika status draft atau revisi dan akses superadmin & pengguna itu bisa edit
            $is_edit = true;
        }else{
            $is_edit = false;
        }

        return $is_edit;
    }
}
