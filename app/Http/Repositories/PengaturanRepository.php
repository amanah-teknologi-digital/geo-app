<?php

namespace App\Http\Repositories;

use App\Models\Pengaturan;

class PengaturanRepository
{
    public function getDataPengaturan(){
        $data = Pengaturan::first();

        return $data;
    }
}
