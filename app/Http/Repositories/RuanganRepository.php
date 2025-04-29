<?php

namespace App\Http\Repositories;

use App\Models\Files;
use App\Models\JadwalRuangan;
use App\Models\JenisRuangan;
use App\Models\JenisSurat;
use App\Models\Ruangan;
use Carbon\Carbon;

class RuanganRepository
{
    public function getDataRuangan($idRuangan){
        $data = Ruangan::with(['pihakupdater','gambar'])->orderBy('is_aktif','DESC')->orderBy('created_at');

        if (!empty($idRuangan)) {
            $data = $data->where('id_ruangan', $idRuangan)->first();
        }else{
            $data = $data->get();
        }

        return $data;
    }

    public function getDataJadwalByIdJadwal($idJadwal){
        $data = JadwalRuangan::find($idJadwal);

        return $data;
    }

    public function getJenisRuangan(){
        $data = JenisRuangan::get();

        return $data;
    }

    public function getDataJadwal($idRuangan){
        $data = JadwalRuangan::where('id_ruangan', $idRuangan)->get();

        return $data;
    }

    public function tambahRuangan($request, $idRuangan, $idFileGambar){
        Ruangan::create([
            'id_ruangan' => $idRuangan,
            'kode_ruangan' => $request->kode_ruangan,
            'nama' => $request->nama_ruangan,
            'jenis_ruangan' => $request->jenis_ruangan,
            'fasilitas' => $request->fasilitas,
            'keterangan' => $request->keterangan,
            'kapasitas' => $request->kapasitas,
            'lokasi' => $request->lokasi,
            'gambar_file' => $idFileGambar,
            'is_aktif' => 1,
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function createOrUpdateFile($idFile, $fileName, $filePath, $fileMime, $fileExt, $fileSize){
        $file = Files::find($idFile);

        if ($file) {
            $file->file_name = $fileName;
            $file->location = $filePath;
            $file->mime = $fileMime;
            $file->ext = $fileExt;
            $file->file_size = $fileSize;
            $file->is_private = 0;
            $file->updated_at = now();
            $file->updater = auth()->user()->id;
            $file->save();
        } else {
            Files::create([
                'id_file' => $idFile,
                'file_name' => $fileName,
                'location' => $filePath,
                'mime' => $fileMime,
                'ext' => $fileExt,
                'file_size' => $fileSize,
                'created_at' => now(),
                'is_private' => 0,
                'updater' => auth()->user()->id
            ]);
        }
    }

    public function updateRuangan($request, $idRuangan){
        $aktif = $request->input('is_aktif', 0);

        $dataRuangan = Ruangan::find($idRuangan);
        $dataRuangan->kode_ruangan = $request->kode_ruangan;
        $dataRuangan->nama = $request->nama_ruangan;
        $dataRuangan->jenis_ruangan = $request->jenis_ruangan;
        $dataRuangan->fasilitas = $request->fasilitas;
        $dataRuangan->keterangan = $request->keterangan;
        $dataRuangan->kapasitas = $request->kapasitas;
        $dataRuangan->lokasi = $request->lokasi;
        $dataRuangan->is_aktif = $aktif;
        $dataRuangan->updated_at = now();
        $dataRuangan->updater = auth()->user()->id;
        $dataRuangan->save();
    }

    public function cekJadwalRuanganBentrok($idRuangan, $hari, $tglMulai, $tglSelesai, $jamMulai, $jamSelesai, $idJadwal){
        // Parse input tanggal
        $tglMulai = Carbon::createFromFormat('d-m-Y', $tglMulai)->format('Y-m-d');
        $tglSelesai = Carbon::createFromFormat('d-m-Y', $tglSelesai)->format('Y-m-d');

        // Parse input jam
        $jamMulai = Carbon::createFromFormat('H:i', $jamMulai)->format('H:i:s');
        $jamSelesai = Carbon::createFromFormat('H:i', $jamSelesai)->format('H:i:s');

        $jadwalBentrok = JadwalRuangan::where('id_ruangan', $idRuangan)
            ->where('day_of_week', $hari)
            ->where(function($query) use ($tglMulai, $tglSelesai, $jamMulai, $jamSelesai) {
                $query->whereBetween('tgl_mulai', [$tglMulai, $tglSelesai])
                    ->orWhereBetween('tgl_selesai', [$tglMulai, $tglSelesai])
                    ->orWhere(function($q) use ($tglMulai, $tglSelesai) {
                        $q->where('tgl_mulai', '<=', $tglMulai)
                            ->where('tgl_selesai', '>=', $tglSelesai);
                    });
            })
            ->where(function($query) use ($jamMulai, $jamSelesai) {
                $query->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            });

        if (!empty($idJadwal)){
            $jadwalBentrok = $jadwalBentrok->where('id_jadwal', '!=', $idJadwal)->exists();
        }else{
            $jadwalBentrok = $jadwalBentrok->exists();
        }

        return $jadwalBentrok;
    }

    public function tambahJadwalRuangan($idJadwal, $idRuangan, $keterangan, $hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai){
        JadwalRuangan::create([
            'id_jadwal' => $idJadwal,
            'id_ruangan' => $idRuangan,
            'keterangan' => $keterangan,
            'day_of_week' => $hari,
            'tgl_mulai' => Carbon::createFromFormat('d-m-Y', $tgl_mulai),
            'tgl_selesai' => Carbon::createFromFormat('d-m-Y', $tgl_selesai),
            'jam_mulai' => Carbon::createFromFormat('H:i', $jam_mulai),
            'jam_selesai' => Carbon::createFromFormat('H:i', $jam_selesai),
            'tipe_jadwal' => 'jadwal',
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);
    }

    public function updateJadwalRuangan($idJadwal, $keterangan, $hari, $tgl_mulai, $tgl_selesai, $jam_mulai, $jam_selesai){
        $jadwalRuangan = JadwalRuangan::find($idJadwal);

        if ($jadwalRuangan) {
            $jadwalRuangan->id_jadwal = $idJadwal;
            $jadwalRuangan->keterangan = $keterangan;
            $jadwalRuangan->day_of_week = $hari;
            $jadwalRuangan->tgl_mulai = Carbon::createFromFormat('d-m-Y', $tgl_mulai);
            $jadwalRuangan->tgl_selesai = Carbon::createFromFormat('d-m-Y', $tgl_selesai);
            $jadwalRuangan->jam_mulai = Carbon::createFromFormat('H:i', $jam_mulai);
            $jadwalRuangan->jam_selesai = Carbon::createFromFormat('H:i', $jam_selesai);
            $jadwalRuangan->updated_at = now();
            $jadwalRuangan->updater = auth()->user()->id;
            $jadwalRuangan->save();
        }
    }

    public function hapusJadwalRuangan($idJadwal){
        $jadwalRuangan = JadwalRuangan::find($idJadwal);

        if ($jadwalRuangan) {
            $jadwalRuangan->delete();
        }
    }
}
