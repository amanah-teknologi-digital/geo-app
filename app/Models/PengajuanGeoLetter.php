<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanGeoLetter extends Model
{
    protected $table = 'pengajuan_geoletter';
    public $timestamps = false;
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = false;
    protected $fillable = [
        'id_pengajuan',
        'pengaju',
        'id_statuspengajuan',
        'id_jenissurat',
        'created_at',
        'keterangan',
        'data_form',
        'updated_at',
        'updater'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function pihakpengaju()
    {
        return $this->belongsTo(User::class, 'pengaju', 'id');
    }

    public function pihakupdater()
    {
        return $this->belongsTo(User::class, 'updater', 'id');
    }

    public function jenissurat()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenissurat', 'id_jenissurat');
    }

    public function statuspengajuan()
    {
        return $this->belongsTo(StatusPengajuan::class, 'id_statuspengajuan', 'id_statuspengajuan');
    }

}
