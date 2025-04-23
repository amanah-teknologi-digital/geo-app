<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisRuangan extends Model
{
    protected $table = 'jenis_ruangan';
    protected $primaryKey = 'id_jenisruangan';
    public $incrementing = true;
    protected $fillable = [
        'id_jenisruangan',
        'nama',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
