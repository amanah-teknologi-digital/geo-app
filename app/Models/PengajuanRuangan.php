<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanRuangan extends Model
{
    protected $table = 'pengajuan_ruangan';
    public $timestamps = false;
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = false;
    protected $fillable = [
        'id_pengajuan',
        'id_ruangan',
        'pengaju',
        'id_statuspengajuan',
        'id_statuspengaju',
        'nama_kegiatan',
        'deskripsi',
        'tgl_mulai',
        'tgl_selesai',
        'jam_mulai',
        'jam_selesai',
        'nama_pengaju',
        'no_hp',
        'email',
        'email_its',
        'kartu_id',
        'created_at',
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

    public function statuspengaju()
    {
        return $this->belongsTo(StatusPengaju::class, 'id_statuspengaju', 'id_statuspengaju');
    }

    public function statuspengajuan()
    {
        return $this->belongsTo(StatusPengajuan::class, 'id_statuspengajuan', 'id_statuspengajuan');
    }

    public function persetujuan()
    {
        return $this->hasMany(PersetujuanRuangan::class, 'id_pengajuan', 'id_pengajuan')->orderBy('created_at');
    }

    public function pengajuandetail()
    {
        return $this->hasMany(PengajuanRuanganDetail::class, 'id_pengajuan', 'id_pengajuan');
    }

}
