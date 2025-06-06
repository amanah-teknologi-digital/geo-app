<?php

namespace App\Http\Services;

use App\Http\Repositories\PengajuanRuanganRepository;
use App\Models\Files;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PengajuanRuanganServices
{
    private $repository;
    private $idAkses;
    public function __construct(PengajuanRuanganRepository $repository)
    {
        $this->repository = $repository;
        $this->idAkses = session('akses_default_id');
    }

    public function getDataPengajuan($id_pengajuan = null){
        $id_akses = $this->idAkses;
        $data = $this->repository->getDataPengajuan($id_pengajuan, $id_akses);

        return $data;
    }

    public function tambahDataPengajuan($idPengajuan, $tglMulai, $tglSelesai, $jamMulai, $jamSelesai, $statusPengaju, $deskripsiKegiatan, $namaKegiatan){
        try {
            $this->repository->tambahDataPengajuan($idPengajuan, $tglMulai, $tglSelesai, $jamMulai, $jamSelesai, $statusPengaju, $deskripsiKegiatan, $namaKegiatan);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tambahDataRuangan($idPengajuan, $idRuangan){
        try {
            foreach ($idRuangan as $ruangan) {
                $this->repository->tambahDataRuangan($idPengajuan, $ruangan);
            }
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tambahDataPeralatan($idPengajuan, $peralatan, $jumlahPeralatan){
        try {
            foreach ($peralatan as $key => $alat) {
                $jumlah = $jumlahPeralatan[$key];
                $this->repository->tambahDataPeralatan($idPengajuan, $alat, $jumlah);
            }
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getDataStatusPeminjam(){
        $data = $this->repository->getDataStatusPeminjam();

        return $data;
    }

    public function getDataRuanganAktif($idRuangan = null, $isEdit = false){
        $data = $this->repository->getDataRuanganAktif($idRuangan, $isEdit);

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
            $this->repository->hapusDetailPengajuanRuangan($id_pengajuan);
            $this->repository->hapusDetailPengajuanPeralatan($id_pengajuan);
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

    public function tambahPersetujuan($id_pengajuan, $id_akses, $id_statuspersetujuan, $keterangan = null)
    {
        try {
            $this->repository->tambahPersetujuan($id_pengajuan, $id_akses, $id_statuspersetujuan, $keterangan);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function cekJadwalRuanganBentrok($idRuangan, $tglMulai, $tglSelesai, $jamMulai, $jamSelesai){
        $data = $this->repository->cekJadwalRuanganBentrok($idRuangan, $tglMulai, $tglSelesai, $jamMulai, $jamSelesai);

        return $data;
    }

    public function getDataRuangan($idRuangan){
        $data = $this->repository->getDataRuangan($idRuangan);

        return $data;
    }

    public function getDataJadwal($idRuangan){
        $data = $this->repository->getDataJadwal($idRuangan);
        $events = [];
        $daysOfWeekMapping = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];

        foreach ($data as $item) {
            // Menentukan tanggal mulai dan selesai
            $startDate = Carbon::parse($item->tgl_mulai);
            $endDate = Carbon::parse($item->tgl_selesai);
            $dayOfWeek = $daysOfWeekMapping[$item->day_of_week - 1]; // Bisa jadi integer atau string seperti "Senin", "Selasa", dst.

            // Mengulang dari tgl_mulai hingga tgl_selesai
            while ($startDate <= $endDate) {
                // Jika hari ini adalah hari yang sesuai (berdasarkan day_of_week)
                if ($startDate->format('l') === $dayOfWeek) {
                    if ($item->tipe_jadwal == 'jadwal'){
                        $cal = 'success';
                    }else{
                        $cal = 'primary';
                    }

                    $events[] = [
                        'id' => $item->id_jadwal,
                        'title' => $item->keterangan,
                        'start' => $startDate->toDateString() . 'T' . $item->jam_mulai,
                        'end' => $startDate->toDateString() . 'T' . $item->jam_selesai,
                        'extendedProps' => [
                            'calendar' => $cal,
                            'type' => $item->tipe_jadwal,
                            'keterangan' => $item->keterangan,
                            'nama_ruangan' => $item->ruangan->nama.' ('.$item->ruangan->kode_ruangan.')',
                            'day_of_week' => $item->day_of_week,
                            'jam_mulai' => $item->jam_mulai,
                            'jam_selesai' => $item->jam_selesai,
                            'tgl_mulai' => $item->tgl_mulai,
                            'tgl_selesai' => $item->tgl_selesai
                        ]
                    ];
                }
                // Pindah ke hari berikutnya
                $startDate->addDay();
            }
        }

        return $events;
    }
}
