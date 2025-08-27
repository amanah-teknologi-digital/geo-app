<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalRuangan extends Model
{
    protected $table = 'jadwal_ruangan';
    protected $primaryKey = 'id_jadwal';
    public $incrementing = false;
    protected $fillable = [
        'id_jadwal',
        'id_ruangan',
        'ref_id_booking',
        'keterangan',
        'day_of_week',
        'jam_mulai',
        'jam_selesai',
        'tgl_mulai',
        'tgl_selesai',
        'tipe_jadwal',
        'created_at',
        'updated_at',
        'updater'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function ruangan(){
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }

    public function pihakupdate(){
        return $this->belongsTo(User::class, 'updater', 'id');
    }
}
