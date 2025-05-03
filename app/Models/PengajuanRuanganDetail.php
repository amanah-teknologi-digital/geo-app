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
        'id_ruangan'
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }
}
