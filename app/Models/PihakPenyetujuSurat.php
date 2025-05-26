<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PihakPenyetujuSurat extends Model
{
    protected $table = 'pihak_penyetujusurat';
    protected $primaryKey = 'id_pihakpenyetuju';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_pihakpenyetuju',
        'id_jenissurat',
        'id_penyetuju',
        'urutan',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
