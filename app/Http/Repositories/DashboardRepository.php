<?php

namespace App\Http\Repositories;

use App\Models\PengajuanPersuratan;
use App\Models\PengajuanRuangan;
use App\Models\SurveyKepuasanPersuratan;
use App\Models\SurveyKepuasanRuangan;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function getDataTotalPersuratan($tahun, $idUser){
        $data = PengajuanPersuratan::selectRaw('
                YEAR(created_at) as tahun,
                COUNT(id_pengajuan) as total_pengajuan,
                COUNT(CASE WHEN id_statuspengajuan = 1 THEN 1 END) as disetujui,
                COUNT(CASE WHEN id_statuspengajuan = 3 THEN 1 END) as ditolak,
                COUNT(CASE WHEN id_statuspengajuan NOT IN (1,3) THEN 1 END) as on_proses')
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderByDesc('tahun')
            ->where(DB::raw('YEAR(created_at)'), $tahun);
        if (!empty($idUser)){
            $data = $data->where('pengaju', $idUser)->first();
        }else{
            $data = $data->first();
        }

        $data = $data ?? (object) [
            'tahun' => $tahun,
            'total_pengajuan' => 0,
            'disetujui' => 0,
            'ditolak' => 0,
            'on_proses' => 0
        ];

        return $data;
    }

    public function getDataTotalPengajuanRuangan($tahun, $idUser){
        $data = PengajuanRuangan::selectRaw("
            YEAR(created_at) as tahun,
            COUNT(id_pengajuan) as total_pengajuan,
            SUM(CASE WHEN id_tahapan = 10 THEN 1 ELSE 0 END) as disetujui,
            SUM(CASE WHEN EXISTS (
                SELECT 1 FROM persetujuan_ruangan pr
                WHERE pr.id_pengajuan = pengajuan_ruangan.id_pengajuan
                  AND pr.id_statuspersetujuan = 3
            ) THEN 1 ELSE 0 END) as ditolak")
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderByDesc('tahun')
            ->where(DB::raw('YEAR(created_at)'), $tahun);

        if (!empty($idUser)){
            $data = $data->where('pengaju', $idUser)->first();
        }else{
            $data = $data->first();
        }

        $data = $data ?? (object) [
            'tahun' => $tahun,
            'total_pengajuan' => 0,
            'disetujui' => 0,
            'ditolak' => 0,
            'on_proses' => 0
        ];

        $data->on_proses = ($data->total_pengajuan ?? 0) - (($data->disetujui ?? 0) + ($data->ditolak ?? 0));

        return $data;
    }

    public function getDataStatistikPersuratan($tahun, $idUser){
        $data = PengajuanPersuratan::with('persetujuan')
            ->where(DB::raw('YEAR(created_at)'), $tahun);

        if (!empty($idUser)){
            $data = $data->where('pengaju', $idUser)->get();
        }else{
            $data = $data->get();
        }

        return $data;
    }

    public function getDataStatistikRuangan($tahun, $idUser){
        $data = PengajuanRuangan::with('persetujuan')
            ->where(DB::raw('YEAR(created_at)'), $tahun);

        if (!empty($idUser)){
            $data = $data->where('pengaju', $idUser)->get();
        }else{
            $data = $data->get();
        }

        return $data;
    }

    public function getDataPengajuanOnly($idPengajuan){
        $data = PengajuanPersuratan::with(['persetujuan','pihakpenyetuju'])->where('id_pengajuan', $idPengajuan)->first();

        return $data;
    }

    public function getDataNotifSurat($idAkses){
        $id_pengguna = auth()->user()->id;
        $data = PengajuanPersuratan::with(['persetujuan','pihakpenyetuju'])->whereNotIn('id_statuspengajuan', [1, 3])
            ->where(function ($query) use ($idAkses, $id_pengguna) {
                // untuk pengguna biasa
                if ($idAkses == 8) {
                    $query->where('pengaju', $id_pengguna);
                }

                // admin geo: status tidak draft
                if ($idAkses == 2) {
                    $query->where('id_statuspengajuan', '!=', 0);
                }

                // kondisi umum untuk semua yang bukan superadmin
                if ($idAkses != 1) {
                    $query->orWhereHas('pihakpenyetuju', function ($q) use ($id_pengguna) {
                        $q->where('id_penyetuju', '!=', $id_pengguna);
                    });
                }
            })
            ->get();

        return $data;
    }

    public function getDataNotifRuangan($idAkses){
        $data = PengajuanRuangan::with(['persetujuan'])->whereDoesntHave('persetujuan', function($q){
            $q->where('id_statuspersetujuan', 3); //jika sudah disetujui pada tahapan verifikasi admin
        })->where('id_tahapan', '!=', 10);

        $id_pengguna = auth()->user()->id;

        // khusus untuk akses pemeriksa dan pengguna, berdasarkan penunjukan atau yang mengajukan saja
        if (in_array($idAkses, [8,9])) {
            $data = $data->where(function ($q) use ($id_pengguna) {
                $q->where('pengaju', $id_pengguna) // sebagai pemohon
                ->orWhere('pemeriksa_awal', $id_pengguna)
                    ->orWhere('pemeriksa_akhir', $id_pengguna);
            });
        }

        if ($idAkses == 3){ //admin ruangan
            $data = $data->where('id_tahapan', '!=', 1); // pengajuan tidak draft
        }

        if ($idAkses == 9){ //pemeriksa
            $data = $data->whereHas('persetujuan', function($q){
                $q->where('id_statuspersetujuan', 1)->where('id_tahapan', 2); //jika sudah disetujui pada tahapan verifikasi admin
            });
        }

        if ($idAkses == 6){ //kasubbag
            $data = $data->whereHas('persetujuan', function($q){
                $q->where('id_statuspersetujuan', 1)->where('id_tahapan', 3); //jika sudah disetujui pada tahapan pemeriksaan awal
            });
        }

        if ($idAkses == 7){ //kadep
            $data = $data->whereHas('persetujuan', function($q){
                $q->where('id_statuspersetujuan', 1)->where('id_tahapan', 4); //jika sudah disetujui pada tahapan verifikasi kasubbag
            });
        }

        $data = $data->get();

        return $data;
    }

    public function getSurveyKepuasan(){
        $data = SurveyKepuasanPersuratan::selectRaw('
                COUNT(*) as total_responden,
                AVG(rating) as average_rating,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5')->first();

        return $data;
    }

    public function getSurveyKepuasanRuang(){
        $data = SurveyKepuasanRuangan::selectRaw('
                COUNT(*) as total_responden,
                AVG(rating) as average_rating,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5')->first();

        return $data;
    }
}
