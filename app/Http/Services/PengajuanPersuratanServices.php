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
    private $idAkses;
    public function __construct(PengajuanPersuratanRepository $repository)
    {
        $this->repository = $repository;
        $this->idAkses = session('akses_default_id');
    }

    public function getDataPengajuan($id_pengajuan = null){
        $id_akses = $this->idAkses;
        $data = $this->repository->getDataPengajuan($id_pengajuan, $id_akses);

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

    public function getJenisSurat($id_jenissurat = null, $isEdit = false){
        $data = $this->repository->getJenisSurat($id_jenissurat, $isEdit);

        return $data;
    }

    public function getDataFile($idFile){
        $data = $this->repository->getDataFile($idFile);

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

    public function updateDataPemohon($id_pengajuan){
        try {
            $this->repository->updateDataPemohon($id_pengajuan);
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
        $id_akses = $this->idAkses;

        if (($id_statuspengajuan == 0 OR $id_statuspengajuan == 4) && in_array($id_akses, [1, 8])) { //jika status draft atau revisi dan akses superadmin & pengguna itu bisa edit
            $is_edit = true;
        }else{
            $is_edit = false;
        }

        return $is_edit;
    }

    public function checkAksesTambah($idAkses){
        if (in_array($idAkses,[1,8])){ //cuma bisa super admin & pengguna
            $isTambah = true;
        }else{
            $isTambah = false;
        }

        return $isTambah;
    }

    public function getStatusVerifikasi($id_pengajuan){
        $id_akses = $this->idAkses;
        $dataPengajuan = $this->getDataPengajuan($id_pengajuan);

        $must_aprove = '';
        $message = '';
        $data = [];
        $must_akses = 0;
        $must_sebagai = '';

        if ($id_akses == 1 || $dataPengajuan->id_statuspengajuan == 1 || $dataPengajuan->id_statuspengajuan == 3){
            $persetujuanTerakhir = $this->repository->getPersetujuanTerakhirSuper($id_pengajuan);
        }else{
            $persetujuanTerakhir = $this->repository->getPersetujuanTerakhir($id_pengajuan, $id_akses);
        }

        if ($id_akses == 1){ //super admin
            if ($dataPengajuan->id_statuspengajuan == 0){ //draft
                $must_aprove = 'AJUKAN';
                $must_akses = 8;
                $must_sebagai = 'Pengguna';
            }else{
                if ($dataPengajuan->id_statuspengajuan == 2) { //verifikator sebagai
                    $must_aprove = 'VERIFIKASI';
                    $must_akses = 2;
                    $must_sebagai = 'Admin Geo Letter';
                }if ($dataPengajuan->id_statuspengajuan == 4){ //jika revisi harus sudah direvisi
                    $must_aprove = 'SUDAH DIREVISI';
                    $must_akses = 8;
                    $must_sebagai = 'Pengguna';
                }else if ($dataPengajuan->id_statuspengajuan == 5){ //jika sudah revisi harus diverifikasi
                    $must_aprove = 'VERIFIKASI';
                    $must_akses = 2;
                    $must_sebagai = 'Admin Geo Letter';
                }else{
                    if (empty($persetujuanTerakhir)){
                        $message = 'Persetujuan Kosong!';
                    }else{
                        $data = $persetujuanTerakhir;
                    }
                }
            }
        }elseif ($id_akses == 2){ //admin geo letter
            if ($dataPengajuan->id_statuspengajuan == 0){ //draft
                $message = 'Pengajuan Belum Diajukan!';
            }else{
                if (empty($persetujuanTerakhir)){
                    $must_aprove = 'VERIFIKASI';
                }else{
                    if ($dataPengajuan->id_statuspengajuan == 5){ //sudah direvisi
                        $must_aprove = 'VERIFIKASI';
                    }else{
                        if (empty($persetujuanTerakhir)){
                            $message = 'Persetujuan Kosong!';
                        }else{
                            $data = $persetujuanTerakhir;
                        }
                    }
                }
            }
        }elseif ($id_akses == 8){ //pengguna
            if ($dataPengajuan->id_statuspengajuan == 0){ //draft
                $must_aprove = 'AJUKAN';
            }else if ($dataPengajuan->id_statuspengajuan == 4){ //jika revisi harus sudah direvisi
                $must_aprove = 'SUDAH DIREVISI';
            }else{
                if (empty($persetujuanTerakhir)){
                    $message = 'Persetujuan Kosong!';
                }else{
                    $data = $persetujuanTerakhir;
                }
            }
        }

        return [
            'must_aprove' => $must_aprove,
            'message' => $message,
            'data' => $data,
            'must_akses' => $must_akses,
            'must_sebagai' => $must_sebagai
        ];
    }

    public function ajukanPengajuan($id_pengajuan){
        try {
            $this->repository->ajukanPengajuan($id_pengajuan);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function setujuiPengajuan($id_pengajuan){
        try {
            $this->repository->setujuiPengajuan($id_pengajuan);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function revisiPengajuan($id_pengajuan){
        try {
            $this->repository->revisiPengajuan($id_pengajuan);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function sudahRevisiPengajuan($id_pengajuan){
        try {
            $this->repository->sudahRevisiPengajuan($id_pengajuan);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tolakPengajuan($id_pengajuan){
        try {
            $this->repository->tolakPengajuan($id_pengajuan);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getHtmlStatusPengajuan($id_statuspengajuan, $id_akses, $dataPersetujuan){
        $html = '';

        if ($id_akses == 8){ //pengguna
            if ($id_statuspengajuan == 0){
                $html .= '<br><i class="text-danger small">(Belum Diajukan)</i>';
            }elseif ($id_statuspengajuan == 4){
                $html .= '<br><i class="text-danger small">(Pengajuan Direvisi)</i>';
            }
        }

        if ($id_akses == 2){ //admin
            if ($id_statuspengajuan == 5){
                $html .= '<br><i class="text-danger small">(Belum Diverifikasi)</i>';
            }elseif ($id_statuspengajuan == 2){
                if ($dataPersetujuan->isNotEmpty()){
                    $id_persetujuan = $dataPersetujuan->first(function ($item) {
                        return $item->id_akses == 2;
                    })->id_persetujuan ?? null;

                    if (empty($id_persetujuan)){
                        $html .= '<br><i class="text-danger small">(Belum Diverifikasi)</i>';
                    }
                }else{
                    $html .= '<br><i class="text-danger small">(Belum Diajukan)</i>';
                }
            }
        }

        if ($id_akses == 1){ //superadmin
            if ($id_statuspengajuan == 0){
                $html .= '<br><i class="text-danger small">(Belum Diajukan)</i>';
            }elseif ($id_statuspengajuan == 4){
                $html .= '<br><i class="text-danger small">(Pengajuan Direvisi)</i>';
            }elseif ($id_statuspengajuan == 5){
                $html .= '<br><i class="text-danger small">(Belum Diverifikasi)</i>';
            }elseif ($id_statuspengajuan == 2){
                if ($dataPersetujuan->isNotEmpty()){
                    $id_persetujuan = $dataPersetujuan->first(function ($item) {
                        return $item->id_akses == 2;
                    })->id_persetujuan ?? null;

                    if (empty($id_persetujuan)){
                        $html .= '<br><i class="text-danger small">(Belum Diverifikasi)</i>';
                    }
                }else{
                    $html .= '<br><i class="text-danger small">(Belum Diajukan)</i>';
                }
            }
        }

        return $html;
    }

    public function tambahPersetujuan($id_pengajuan, $id_akses, $id_statuspersetujuan, $keterangan = null){
        try {
            $this->repository->tambahPersetujuan($id_pengajuan, $id_akses, $id_statuspersetujuan, $keterangan);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tambahFile($file, $id_file){
        try {
            $fileName = $file->getClientOriginalName();
            $fileMime = $file->getClientMimeType();
            $fileExt = $file->getClientOriginalExtension();
            $newFileName = $id_file.'.'.$fileExt;
            $fileSize = $file->getSize();
            $filePath = $file->storeAs('file_surat', $newFileName, 'public');

            //save file data ke database
            $this->repository->tambahFile($id_file, $fileName, $filePath, $fileMime, $fileExt, $fileSize);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function hapusFile($idPengajuan, $idFile, $location){
        try {
            Storage::disk('public')->delete($location);

            //hapus file dari database
            $this->repository->hapusFilePengajuan($idPengajuan, $idFile);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tambahFileSurat($idPengajuan, $idFile){
        try {
            $this->repository->tambahFileSurat($idPengajuan, $idFile);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
