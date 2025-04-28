<?php

namespace App\Http\Services;

use App\Http\Repositories\RuanganRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

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
            $filePath = $file->storeAs('ruangan', $newFileName, 'public');

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

    public function getDataJadwal($idRuangan){
        $data = $this->repository->getDataJadwal($idRuangan);
        $events = [];
        $daysOfWeekMapping = [
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday',
            5 => 'Saturday',
            6 => 'Sunday'
        ];

        foreach ($data as $item) {
            // Menentukan tanggal mulai dan selesai
            $startDate = Carbon::parse($item->tgl_mulai);
            $endDate = Carbon::parse($item->tgl_selesai);
            $dayOfWeek = $daysOfWeekMapping[$item->day_of_week]; // Bisa jadi integer atau string seperti "Senin", "Selasa", dst.

            // Mengulang dari tgl_mulai hingga tgl_selesai
            while ($startDate <= $endDate) {
                // Jika hari ini adalah hari yang sesuai (berdasarkan day_of_week)
                if ($startDate->format('l') === $dayOfWeek) {
                    $events[] = [
                        'id' => $item->id_jadwal,
                        'title' => $item->keterangan,
                        'start' => $startDate->toDateString() . 'T' . $item->jam_mulai,
                        'end' => $startDate->toDateString() . 'T' . $item->jam_selesai,
                        'extendedProps' => [
                            'calendar' => 'success',
                            'type' => 'jadwal'
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
