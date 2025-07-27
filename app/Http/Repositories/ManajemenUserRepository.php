<?php

namespace App\Http\Repositories;

use App\Models\Akses;
use App\Models\AksesUser;
use App\Models\User;

class ManajemenUserRepository
{
    public function getDataUser($idUser){
        $data = User::with(['aksesuser'])->orderBy('created_at', 'DESC');

        if (!empty($idUser)) {
            $data = $data->where('id', $idUser)->first();
        }

        return $data;
    }

    public function getListAkses($listAkses){
        $data = Akses::where('is_aktif', 1)->whereNotIn('id_akses', $listAkses)->get();

        return $data;
    }

    public function tambahAksesUser($idAkses, $idUser){
        AksesUser::create([
            'id_akses' => $idAkses,
            'id_user' => $idUser,
            'is_default' => 0,
            'created_at' => now()
        ]);
    }

    public function setDefaultAkses($idAkses, $idUser){
        AksesUser::where('id_user', $idUser)->update(['is_default' => 0]);

        $data = AksesUser::where('id_user', $idUser)->where('id_akses', $idAkses);
        if (!empty($data)) {
            $data->update([
                'is_default' => 1
            ]);
        }
    }

    public function hapusAkses($idAkses, $idUser){
        $data = AksesUser::where('id_user', $idUser)->where('id_akses', $idAkses);
        if (!empty($data)) {
            $data->delete();
        }
    }
}
