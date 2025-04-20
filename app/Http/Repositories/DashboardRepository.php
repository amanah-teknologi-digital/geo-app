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

    public function getDataNotifSurat($idAkses){
        $data = PengajuanPersuratan::with(['persetujuan']);

        $id_pengguna = auth()->user()->id;
        if ($idAkses == 8){ //pengguna
            $data = $data->where('pengaju', $id_pengguna)->get();
        }

        if ($idAkses == 2){ // admin geo harus status tidak draft
            $data = $data->where('id_statuspengajuan', '!=', 0)->get();
        }

        if ($idAkses == 1){ //super admin
            $data = $data->get();
        }

        return $data;
    }
}
