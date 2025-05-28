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
        'nama',
        'urutan',
        'id_penyetuju',
        'created_at',
        'updated_at',
        'updater'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function userpenyetuju()
    {
        return $this->belongsTo(User::class, 'id_penyetuju','id');
    }
}
