<?php

namespace App\Http\Repositories;

use App\Models\PengajuanPersuratan;
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
}
