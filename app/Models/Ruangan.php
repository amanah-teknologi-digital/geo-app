<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id_ruangan',
        'kode_ruangan',
        'jenis_ruangan',
        'nama',
        'fasilitas',
        'keterangan',
        'kapasitas',
        'lokasi',
        'gambar_file',
        'is_aktif',
        'created_at',
        'updated_at',
        'updater'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pihakupdater()
    {
        return $this->belongsTo(User::class, 'updater', 'id');
    }

    public function gambar()
    {
        return $this->belongsTo(Files::class, 'gambar_file', 'id_file');
    }
}
