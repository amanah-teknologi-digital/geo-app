<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahapanPeminjamanRuangan extends Model
{
    protected $table = 'tahapan_peminjamanruang';
    protected $primaryKey = 'id_tahapan';
    public $incrementing = false;
    protected $fillable = [
        'id_tahapan',
        'nama',
        'urutan',
        'created_at',
        'updated_at'
    ];
}
