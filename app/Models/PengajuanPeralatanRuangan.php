<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPeralatanRuangan extends Model
{
    protected $table = 'pengajuan_peralatanruangan';
    protected $primaryKey = 'id_pengajuanperalatan_ruang';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id_pengajuanperalatan_ruang',
        'id_pengajuan',
        'nama_sarana',
        'jumlah',
        'is_valid_awal',
        'is_valid_akhir',
        'keterangan_awal',
        'keterangan_akhir'
    ];
}
