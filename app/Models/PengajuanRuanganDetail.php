<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanRuanganDetail extends Model
{
    protected $table = 'pengajuan_ruangandetail';
    public $timestamps = false;
    protected $primaryKey = 'id_pengajuanruangan_detail';
    public $incrementing = false;
    protected $fillable = [
        'id_pengajuanruangan_detail',
        'id_pengajuan',
        'id_jenissarana',
        'nama_sarana',
        'jumlah'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function jenissarana()
    {
        return $this->belongsTo(JenisSarana::class, 'id_jenissarana', 'id_jenissarana');
    }
}
