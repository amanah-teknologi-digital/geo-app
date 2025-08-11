<?php

namespace App\Http\Services;

use App\Http\Repositories\RuanganRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RuanganServices
{
    private $repository;
    public function __construct(RuanganRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDataRuangan($idRuangan = null){
        $data = $this->repository->getDataRuangan($idRuangan);

        return $data;
    }

    public function getDataJadwalByIdJadwal($idJadwal){
        $data = $this->repository->getDataJadwalByIdJadwal($idJadwal);

        return $data;
    }

    public function getJenisRuangan(){
        $data = $this->repository->getJenisRuangan();

        return $data;
    }

    public function checkAksesTambah($idAkses){
        if (in_array($idAkses,[1,3])){ //cuma bisa super admin & admin
            $isTambah = true;
        }else{
            $isTambah = false;
        }

        return $isTambah;
    }

    public function checkAksesEdit($idAkses){
        if (in_array($idAkses,[1,3])){ //cuma bisa super admin & admin
            $isEdit = true;
        }else{
            $isEdit = false;
        }

        return $isEdit;
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
            $filePath = 'ruangan/' . $newFileName;
            Storage::disk('public')->put($filePath, $encryptedFileContents);

            //save file data ke database
            $this->repository->createOrUpdateFile($idFile, $fileName, $filePath, $fileMime, $fileExt, $fileSize);
        }catch(Exception $e){
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tambahRuangan($request, $idRuangan, $idFileGambar){
        try {
            $request->fasilitas = $this->getDataJsonFasilitas($request->fasilitas);

            $this->repository->tambahRuangan($request, $idRuangan, $idFileGambar);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function hapusJadwalRuangan($idJadwal){
        try {
            $this->repository->hapusJadwalRuangan($idJadwal);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getDataJsonFasilitas($dataFasilitas){
        $configFasilitas = config('listfasilitas', []);
        $flatFasilitas = [];

        // Flatten semua fasilitas jadi key => full data
        foreach ($configFasilitas as $kategori => $items) {
            foreach ($items as $item) {
                $flatFasilitas[$item['id']] = $item;
            }
        }

        // Ambil fasilitas yang dipilih dan buat array final
        $result = [];

        foreach ($dataFasilitas as $id) {
            if (isset($flatFasilitas[$id])) {
                $result[] = [
                    'id'   => $id,
                    'text' => $flatFasilitas[$id]['text'],
                    'icon' => $flatFasilitas[$id]['icon'],
                ];
            }
        }

        // Simpan sebagai JSON
        $jsonFasilitas = json_encode($result, JSON_PRETTY_PRINT);

        return $jsonFasilitas;
    }

    public function updateRuangan($request, $idRuangan){
        try {
            $request->fasilitas = $this->getDataJsonFasilitas($request->fasilitas);

            $this->repository->updateRuangan($request, $idRuangan);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function tambahJadwalRuangan($idJadwal, $idRuangan, $keterangan, $hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai){
        try {
            $this->repository->tambahJadwalRuangan($idJadwal, $idRuangan, $keterangan, $hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function updateJadwalRuangan($idJadwal, $keterangan, $hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai){
        try {
            $this->repository->updateJadwalRuangan($idJadwal, $keterangan, $hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai);
        }catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
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

    public function cekJadwalRuanganBentrok($idRuangan, $hari, $tglMulai, $tglSelesai, $jamMulai, $jamSelesai, $idJadwal = null){
        $data = $this->repository->cekJadwalRuanganBentrok($idRuangan, $hari, $tglMulai, $tglSelesai, $jamMulai, $jamSelesai, $idJadwal);

        return $data;
    }
}
