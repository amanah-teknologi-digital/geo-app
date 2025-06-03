<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';
    protected $primaryKey = 'id_jenissurat';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id_jenissurat',
        'nama',
        'default_form',
        'is_aktif',
        'is_datapendukung',
        'nama_datapendukung',
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

    public function pengajuansurat()
    {
        return $this->belongsTo(PengajuanPersuratan::class, 'id_jenissurat', 'id_jenissurat');
    }

    public function pihakpenyetujusurat()
    {
        return $this->hasMany(PihakPenyetujuSurat::class, 'id_jenissurat', 'id_jenissurat')->orderBy('urutan');
    }
}
