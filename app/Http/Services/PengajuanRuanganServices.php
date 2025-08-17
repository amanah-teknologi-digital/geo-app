<?php

namespace App\Http\Services;

use App\Http\Repositories\PengajuanRuanganRepository;
use App\Models\Files;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Nonstandard\Uuid;

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

    public function getDataPengaturan(){
        $data = $this->repository->getDataPengaturan();

        return $data;
    }

    public function getDataPengajuanOnly($id_pengajuan){
        $id_akses = $this->idAkses;
        $data = $this->repository->getDataPengajuanOnly($id_pengajuan);

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

    public function updateTahapanPengajuan($idPengajuan, $idTahapan){
        try {
            $this->repository->updateTahapanPengajuan($idPengajuan, $idTahapan);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function updatePemeriksaAwal($idPengajuan, $userPemeriksaAwal){
        try {
            $this->repository->updatePemeriksaAwal($idPengajuan, $userPemeriksaAwal);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function updatePemeriksaAkhir($idPengajuan, $userPemeriksaAkhir){
        try {
            $this->repository->updatePemeriksaAkhir($idPengajuan, $userPemeriksaAkhir);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
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

    public function checkOtoritasPengajuan($idPengajuan){
        $id_akses = $this->idAkses;

        $is_edit = true;

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

    function getStatusPersetujuanTerakhir($dataPersetujuan){
        $adminSudahSetuju           = $dataPersetujuan->where('id_tahapan', 2)->sortByDesc('created_at')->first();
        $pemeriksaAwalSudahSetuju   = $dataPersetujuan->where('id_tahapan', 3)->sortByDesc('created_at')->first();
        $kasubbagSudahSetuju        = $dataPersetujuan->where('id_tahapan', 4)->sortByDesc('created_at')->first();
        $kadepSudahSetuju           = $dataPersetujuan->where('id_tahapan', 5)->sortByDesc('created_at')->first();
        $sudahPengembalian          = $dataPersetujuan->where('id_tahapan', 6)->sortByDesc('created_at')->first();
        $adminVerifikasiPengembalian= $dataPersetujuan->where('id_tahapan', 7)->sortByDesc('created_at')->first();
        $pemeriksaAkhirSudahSetuju  = $dataPersetujuan->where('id_tahapan', 8)->sortByDesc('created_at')->first();
        $sudahVerifikasiPengembalian= $dataPersetujuan->where('id_tahapan', 9)->sortByDesc('created_at')->first();

        return compact(
            'adminSudahSetuju',
            'pemeriksaAwalSudahSetuju',
            'kasubbagSudahSetuju',
            'kadepSudahSetuju',
            'sudahPengembalian',
            'adminVerifikasiPengembalian',
            'pemeriksaAkhirSudahSetuju',
            'sudahVerifikasiPengembalian'
        );
    }

    public function getStatusVerifikasi($id_pengajuan, $namaLayananRuang, $dataPengajuan = null, $idAkses = null){
        if (empty($idAkses)){
            $id_akses = $this->idAkses;
        }else{
            $id_akses = $idAkses;
        }

        $idUser = auth()->user()->id;

        if (empty($dataPengajuan)) {
            $dataPengajuan = $this->repository->getDataPengajuanOnly($id_pengajuan);
        }

        $tahapan = $dataPengajuan->id_tahapan;
        $userPengaju = $dataPengajuan->pengaju;
        $userPemeriksaAwal = $dataPengajuan->pemeriksa_awal;
        $userPemeriksaAkhir = $dataPengajuan->pemeriksa_akhir;
        $dataPersetujuan = $dataPengajuan->persetujuan;
        extract($this->getStatusPersetujuanTerakhir($dataPersetujuan));

        $must_aprove = '';
        $message = '';
        $data = [];
        $must_akses = 0;
        $tahapan_next = 0;
        $must_sebagai = '';
        $label_verifikasi = '';

        $persetujuanTerakhir = $dataPersetujuan->sortByDesc('created_at')->first();

        if ($id_akses == 1){ //super admin
            if ($tahapan == 1){ //draft
                $must_aprove = 'AJUKAN';
                $must_akses = 8;
                $tahapan_next = 2;
                $must_sebagai = 'Pengguna';
                $label_verifikasi = 'Ajukan';
            }else if ($tahapan == 2) { // Verifikasi Admin
                if (!$adminSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $must_akses = 3;
                    $tahapan_next = 3;
                    $must_sebagai = 'Admin ' . $namaLayananRuang;
                    $label_verifikasi = 'Setujui';
                }
            }else if ($tahapan == 3) { // Pemeriksaaan Awal
                if (!$pemeriksaAwalSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $must_akses = 9;
                    $tahapan_next = 4;
                    $must_sebagai = 'Pemeriksa Awal';
                    $label_verifikasi = 'Verifikasi';
                }
            }else if ($tahapan == 4) { // Verifikasi Kasubbag
                if (!$kasubbagSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $must_akses = 6;
                    $tahapan_next = 5;
                    $must_sebagai = 'Kasubbag';
                    $label_verifikasi = 'Setujui';
                }
            }else if ($tahapan == 5) { // Verifikasi Kadep
                if (!$kadepSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $must_akses = 7;
                    $tahapan_next = 6;
                    $must_sebagai = 'Kadep';
                    $label_verifikasi = 'Setujui';
                }
            }else if ($tahapan == 6) { // Pengajuan Berjalan
                if (!$sudahPengembalian) {
                    $must_aprove = 'PENGEMBALIAN';
                    $must_akses = 8;
                    $tahapan_next = 7;
                    $must_sebagai = 'Pengguna';
                    $label_verifikasi = 'Kembalikan Ruangan';
                }
            }else if ($tahapan == 7) { // Pengajuan Pengembalian
                if (!$adminVerifikasiPengembalian) {
                    $must_aprove = 'VERIFIKASI';
                    $must_akses = 3;
                    $tahapan_next = 8;
                    $must_sebagai = 'Admin ' . $namaLayananRuang;
                    $label_verifikasi = 'Setujui';
                }
            }else if ($tahapan == 8) { // Pemeriksaan Akhir
                if (!$pemeriksaAkhirSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $must_akses = 9;
                    $tahapan_next = 9;
                    $must_sebagai = 'Pemeriksa Akhir';
                    $label_verifikasi = 'Verifikasi';
                }
            }else if ($tahapan == 9) { // Verifikasi Pengembalian
                if (!$sudahVerifikasiPengembalian) {
                    $must_aprove = 'VERIFIKASI';
                    $must_akses = 6;
                    $tahapan_next = 10;
                    $must_sebagai = 'Kasubbag';
                    $label_verifikasi = 'Setujui';
                }
            }else{
                if (empty($persetujuanTerakhir)){
                    $message = 'Persetujuan Kosong!';
                }else{
                    $data = $persetujuanTerakhir;
                }
            }
        }else if ($id_akses == 3){ //admin ruang
            if ($tahapan == 2) { // Verifikasi Admin
                if (!$adminSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $tahapan_next = 3;
                    $label_verifikasi = 'Setujui';
                }else{
                    if (empty($persetujuanTerakhir)){
                        $message = 'Persetujuan Kosong!';
                    }else{
                        $data = $persetujuanTerakhir;
                    }
                }
            }else if ($tahapan == 7) { // Pengajuan Pengembalian
                if (!$adminVerifikasiPengembalian) {
                    $must_aprove = 'VERIFIKASI';
                    $tahapan_next = 8;
                    $label_verifikasi = 'Setujui';
                }else{
                    if (empty($persetujuanTerakhir)){
                        $message = 'Persetujuan Kosong!';
                    }else{
                        $data = $persetujuanTerakhir;
                    }
                }
            }else{
                if (empty($persetujuanTerakhir)){
                    $message = 'Persetujuan Kosong!';
                }else{
                    $data = $persetujuanTerakhir;
                }
            }
        }else if ($id_akses == 9){ // Pemeriksa
            if ($tahapan == 3 && $userPemeriksaAwal == $idUser) { // Pemeriksaaan Awal
                if (!$pemeriksaAwalSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $tahapan_next = 4;
                    $label_verifikasi = 'Verifikasi';
                }else{
                    if (empty($persetujuanTerakhir)){
                        $message = 'Persetujuan Kosong!';
                    }else{
                        $data = $persetujuanTerakhir;
                    }
                }
            }else if ($tahapan == 8 && $userPemeriksaAkhir == $idUser) { // Pemeriksaan Akhir
                if (!$pemeriksaAkhirSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $tahapan_next = 9;
                    $label_verifikasi = 'Verifikasi';
                }else{
                    if (empty($persetujuanTerakhir)){
                        $message = 'Persetujuan Kosong!';
                    }else{
                        $data = $persetujuanTerakhir;
                    }
                }
            }else{
                if (empty($persetujuanTerakhir)){
                    $message = 'Persetujuan Kosong!';
                }else{
                    $data = $persetujuanTerakhir;
                }
            }
        }else if ($id_akses == 6){ //kasubbag
            if ($tahapan == 4) { // Verifikasi Kasubbag
                if (!$kasubbagSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $tahapan_next = 5;
                    $label_verifikasi = 'Setujui';
                }else{
                    if (empty($persetujuanTerakhir)){
                        $message = 'Persetujuan Kosong!';
                    }else{
                        $data = $persetujuanTerakhir;
                    }
                }
            }else if ($tahapan == 9) { // Verifikasi Pengembalian
                if (!$sudahVerifikasiPengembalian) {
                    $must_aprove = 'VERIFIKASI';
                    $tahapan_next = 10;
                    $label_verifikasi = 'Setujui';
                }else{
                    if (empty($persetujuanTerakhir)){
                        $message = 'Persetujuan Kosong!';
                    }else{
                        $data = $persetujuanTerakhir;
                    }
                }
            }else{
                if (empty($persetujuanTerakhir)){
                    $message = 'Persetujuan Kosong!';
                }else{
                    $data = $persetujuanTerakhir;
                }
            }
        }else if ($id_akses == 7){ //kadep
            if ($tahapan == 5) { // Verifikasi Kadep
                if (!$kadepSudahSetuju) {
                    $must_aprove = 'VERIFIKASI';
                    $tahapan_next = 6;
                    $label_verifikasi = 'Setujui';
                }else{
                    if (empty($persetujuanTerakhir)){
                        $message = 'Persetujuan Kosong!';
                    }else{
                        $data = $persetujuanTerakhir;
                    }
                }
            }else{
                if (empty($persetujuanTerakhir)){
                    $message = 'Persetujuan Kosong!';
                }else{
                    $data = $persetujuanTerakhir;
                }
            }
        }else if ($id_akses == 8 && $userPengaju == $idUser){ //pengguna
            if ($tahapan == 1){ //draft
                $must_aprove = 'AJUKAN';
                $tahapan_next = 2;
                $label_verifikasi = 'Ajukan';
            }else if ($tahapan == 6) { // Pengajuan Berjalan
                if (!$sudahPengembalian) {
                    $must_aprove = 'PENGEMBALIAN';
                    $tahapan_next = 7;
                    $label_verifikasi = 'Kembalikan Ruangan';
                }
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
            'must_sebagai' => $must_sebagai,
            'tahapan_next' => $tahapan_next,
            'label_verifikasi' => $label_verifikasi
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

    public function getHtmlStatusPengajuan($idPengajuan, $namaLayananRuang, $dataPengajuan){
        $html = '';
        $dataVerifikasi = $this->getStatusVerifikasi($idPengajuan, $namaLayananRuang, $dataPengajuan);
        $mustApprove = $dataVerifikasi['must_aprove'];
        $dataPersetujuan = $dataVerifikasi['data'];
        $message = $dataVerifikasi['message'];

        if ($mustApprove == 'AJUKAN'){
            $html .= '<br><i class="text-danger small">(Belum Diajukan)</i>';
        }

        if ($mustApprove == 'VERIFIKASI'){
            $html .= '<br><i class="text-danger small">(Belum Diverifikasi)</i>';
        }

        if (!empty($dataPersetujuan)){
            if ($dataPersetujuan->id_statuspersetujuan == 3){
                $html .= '<br><i class="text-danger small">(Ditolak)</i>';
            }
        }


        return $html;
    }

    public function tambahPersetujuan($idPengajuan, $idAkses, $idTahapan, $idStatusPersetujuan, $keterangan = null)
    {
        try {
            $this->repository->tambahPersetujuan($idPengajuan, $idAkses, $idTahapan, $idStatusPersetujuan, $keterangan);
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

    public function getUserPemeriksaRuangan($search){
        $data = $this->repository->getUserPemeriksaRuangan($search);

        return $data;
    }

    public function checkFormPemeriksaan($status, $idPengajuan, $input){
        $dataPeralatan = $this->repository->getDataPeralatan($idPengajuan);

        foreach ($dataPeralatan as $item) {
            if (!$input->has('kondisi'.$status.$item->id_pengajuanperalatan_ruang)){
                return false;
                break;
            }

            if (!$input->has('keterangan'.$item->id_pengajuanperalatan_ruang)){
                return false;
                break;
            }

            $this->repository->updateStatusPemeriksaan($status, $item->id_pengajuanperalatan_ruang, $input->input('kondisi'.$status.$item->id_pengajuanperalatan_ruang), $input->input('keterangan'.$item->id_pengajuanperalatan_ruang));
        }

        return true;
    }

    public function tambahFile($file, $idFile){
        try {
            $fileName = $file->getClientOriginalName();
            $fileMime = $file->getClientMimeType();
            $fileExt = $file->getClientOriginalExtension();
            $newFileName = $idFile.'.'.$fileExt;
            $fileSize = $file->getSize();
            $fileContents = file_get_contents($file->getRealPath());
            $encryptedFileContents = Crypt::encrypt($fileContents);
            $filePath = 'file_peminjamanruang/' . $newFileName;
            Storage::disk('public')->put($filePath, $encryptedFileContents);


            //save file data ke database
            $this->repository->tambahFile($idFile, $fileName, $filePath, $fileMime, $fileExt, $fileSize);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function simpanFileSesudahAcara($idPengajuan, $requestFile){
        try {
            foreach ($requestFile as $item) {
                $idFile = strtoupper(Uuid::uuid4()->toString());
                $this->tambahFile($item, $idFile);
                $this->repository->updateFileRuangan($idPengajuan, $idFile);
            }
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

    public function simpanJadwalBookingRuangan($dataPengajuan){
        try {
            $periode = CarbonPeriod::create($dataPengajuan->tgl_mulai, $dataPengajuan->tgl_selesai);
            $idPengajuan = $dataPengajuan->id_pengajuan;
            $listRuangan = $dataPengajuan->pengajuanruangandetail;
            $jadwalHarian = array_map(function ($date) {
                // Return sebuah array, bukan lagi string
                return [
                    'tanggal'    => $date->toDateString(),
                    'nomor_hari' => $date->dayOfWeek + 1, // Logika agar Senin = 2
                    'hari'       => $date->isoFormat('dddd') // Tambahan: nama hari agar lebih jelas
                ];
            }, $periode->toArray());

            foreach ($jadwalHarian as $tgl){
                foreach ($listRuangan as $ruangan){
                    $idJadwal = strtoupper(Uuid::uuid4()->toString());
                    $record = [
                        'id_jadwal' => $idJadwal,
                        'id_ruangan' => $ruangan->id_ruangan,
                        'ref_id_booking' => $idPengajuan,
                        'keterangan' => $dataPengajuan->nama_kegiatan,
                        'day_of_week' => $tgl['nomor_hari'],
                        'jam_mulai' => $dataPengajuan->jam_mulai,
                        'jam_selesai' => $dataPengajuan->jam_selesai,
                        'tgl_mulai' => $tgl['tanggal'],
                        'tgl_selesai' => $tgl['tanggal'],
                        'tipe_jadwal' => 'booking',
                        'created_at' => Carbon::now(),
                        'updater' => auth()->user()->id
                    ];

                    $this->repository->simpanJadwalRuangan($record);
                }
            }
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
