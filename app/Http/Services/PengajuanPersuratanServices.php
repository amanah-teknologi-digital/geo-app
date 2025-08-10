<?php

namespace App\Http\Services;

use App\Http\Repositories\PengajuanPersuratanRepository;
use App\Models\Files;
use App\Models\PengajuanPersuratan;
use App\Models\PersetujuanPersuratan;
use App\Models\PihakPenyetujuPengajuanSurat;
use Exception;
use Illuminate\Support\Facades\Crypt;
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

    public function getStatusVerifikasi($id_pengajuan, $namaLayananSurat, $dataPengajuan = null){
        $id_akses = $this->idAkses;
        $idUser = auth()->user()->id;

        if (empty($dataPengajuan)) {
            $dataPengajuan = $this->repository->getDataPengajuanOnly($id_pengajuan);
        }

        $userPengaju = $dataPengajuan->pengaju;
        $tambahanPenyetuju = $dataPengajuan->pihakpenyetuju;
        $dataPersetujuan = $dataPengajuan->persetujuan;
        $isTambahanPenyetuju = $tambahanPenyetuju->firstWhere('id_penyetuju', $idUser);
        $adminGeoSudahSetuju = $dataPersetujuan->where('id_akses', 2)->where('id_statuspersetujuan', 1)->isNotEmpty();

        $must_aprove = '';
        $message = '';
        $data = [];
        $must_akses = 0;
        $must_pihakpenyetuju = 0;
        $must_sebagai = '';

        if ($id_akses == 1 || $dataPengajuan->id_statuspengajuan == 1 || $dataPengajuan->id_statuspengajuan == 3){
            $persetujuanTerakhir = $dataPersetujuan->sortByDesc('created_at')->first();
        }else{
            $persetujuanTerakhir = $dataPersetujuan->where('id_akses', $id_akses)->sortByDesc('created_at')->first();
        }

        if($isTambahanPenyetuju){
            $persetujuanTerakhirTambahan = $dataPersetujuan->where('id_pihakpenyetuju', $isTambahanPenyetuju->id_pihakpenyetuju)->sortByDesc('created_at')->first();
        }else{
            $persetujuanTerakhirTambahan = [];
        }


        if ($id_akses == 1){ //super admin
            if ($dataPengajuan->id_statuspengajuan == 0){ //draft
                $must_aprove = 'AJUKAN';
                $must_akses = 8;
                $must_sebagai = 'Pengguna';
            }else{
                if ($dataPengajuan->id_statuspengajuan == 2) { //verifikator sebagai
                    if ($isTambahanPenyetuju && $adminGeoSudahSetuju) {
                        $must_aprove = 'VERIFIKASI';
                        $must_pihakpenyetuju = $isTambahanPenyetuju->id_pihakpenyetuju;
                        $must_sebagai = $isTambahanPenyetuju->nama;
                    }else {
                        $must_aprove = 'VERIFIKASI';
                        $must_akses = 2;
                        $must_sebagai = 'Admin ' . $namaLayananSurat;
                    }
                }if ($dataPengajuan->id_statuspengajuan == 4){ //jika revisi harus sudah direvisi
                    $must_aprove = 'SUDAH DIREVISI';
                    $must_akses = 8;
                    $must_sebagai = 'Pengguna';
                }else if ($dataPengajuan->id_statuspengajuan == 5){ //jika sudah revisi harus diverifikasi
                    if (empty($dataPengajuan->user_perevisi)){
                        $must_aprove = 'VERIFIKASI';
                        $must_akses = 2;
                        $must_sebagai = 'Admin '.$namaLayananSurat;
                    }else{
                        if ($isTambahanPenyetuju) {
                            $must_aprove = 'VERIFIKASI';
                            $must_pihakpenyetuju = $isTambahanPenyetuju->id_pihakpenyetuju;
                            $must_sebagai = $isTambahanPenyetuju->nama;
                        }
                    }
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
                    if ($dataPengajuan->id_statuspengajuan == 5 && empty($dataPengajuan->user_perevisi)){ //sudah direvisi
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
        }elseif ($id_akses == 8 && $userPengaju == $idUser){ //pengguna
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

        if ($isTambahanPenyetuju && $must_aprove == ''){
            if ($dataPengajuan->id_statuspengajuan == 0){ //draft
                $message = 'Pengajuan Belum Diajukan!';
            }else{
                if (empty($persetujuanTerakhirTambahan)){
                    if ($adminGeoSudahSetuju) {
                        $must_aprove = 'VERIFIKASI';
                        $must_pihakpenyetuju = $isTambahanPenyetuju->id_pihakpenyetuju;
                    }else{
                        $message = 'Belum Disetujui Admin '.$namaLayananSurat;
                    }
                }else{
                    if ($dataPengajuan->id_statuspengajuan == 5 && $dataPengajuan->user_perevisi == $idUser){ //sudah direvisi
                        $must_aprove = 'VERIFIKASI';
                        $must_pihakpenyetuju = $isTambahanPenyetuju->id_pihakpenyetuju;
                    }else{
                        if (empty($persetujuanTerakhirTambahan)){
                            $message = 'Persetujuan Kosong!';
                        }else{
                            $data = $persetujuanTerakhir;
                        }
                    }
                }
            }
        }

        return [
            'must_aprove' => $must_aprove,
            'message' => $message,
            'data' => $data,
            'must_akses' => $must_akses,
            'must_sebagai' => $must_sebagai,
            'must_pihakpenyetuju' => $must_pihakpenyetuju,
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

    public function revisiPengajuan($id_pengajuan, $idPihakpenyetuju){
        try {
            $this->repository->revisiPengajuan($id_pengajuan, $idPihakpenyetuju);
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

    public function getHtmlStatusPengajuan($idPengajuan, $namaLayananSurat, $dataPengajuan){
        $html = '';
        $dataVerifikasi = $this->getStatusVerifikasi($idPengajuan, $namaLayananSurat, $dataPengajuan);
        $mustApprove = $dataVerifikasi['must_aprove'];
        $message = $dataVerifikasi['message'];

        if ($mustApprove == 'AJUKAN'){
            $html .= '<br><i class="text-danger small">(Belum Diajukan)</i>';
        }

        if ($mustApprove == 'VERIFIKASI'){
            $html .= '<br><i class="text-danger small">(Belum Diverifikasi)</i>';
        }

        if ($mustApprove == 'SUDAH DIREVISI'){
            $html .= '<br><i class="text-danger small">(Pengajuan Direvisi)</i>';
        }

        return $html;
    }

    public function tambahPersetujuan($id_pengajuan, $id_akses, $id_statuspersetujuan, $idPihakpenyetuju, $keterangan = null){
        try {
            $this->repository->tambahPersetujuan($id_pengajuan, $id_akses, $id_statuspersetujuan, $idPihakpenyetuju, $keterangan);
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
            $fileContents = file_get_contents($file->getRealPath());
            $encryptedFileContents = Crypt::encrypt($fileContents);
            $filePath = 'file_surat/' . $newFileName;
            Storage::disk('public')->put($filePath, $encryptedFileContents);


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

    public function hapusFilePendukung($idPengajuan, $idFile, $location){
        try {
            Storage::disk('public')->delete($location);

            //hapus file dari database
            $this->repository->hapusFilePendukung($idPengajuan);
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

    public function updateFileSurat($idPengajuan, $namaDataPendukung, $idFile){
        try {
            $this->repository->updateFileSurat($idPengajuan, $namaDataPendukung, $idFile);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function updateFileSuratUpdate($idPengajuan, $idFile){
        try {
            $this->repository->updateFileSuratUpdate($idPengajuan, $idFile);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function simpanSurveyKepuasan($idKepuasan, $idPengajuan, $keterangan, $rating){
        try {
            $this->repository->simpanSurveyKepuasan($idKepuasan, $idPengajuan, $keterangan, $rating);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getPihakPenyetujuByIdpengajuan($idPengajuan)
    {
        $data = $this->repository->getPihakPenyetujuByIdpengajuan($idPengajuan);

        return $data;
    }
}
