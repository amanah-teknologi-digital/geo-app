<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PihakPenyetujuPengajuanSurat extends Model
{
    protected $table = 'pihak_penyetujupengajuansurat';
    protected $primaryKey = 'id_pihakpenyetuju';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_pihakpenyetuju',
        'id_pengajuan',
        'id_penyetuju',
        'nama',
        'urutan'
    ];

    public function userpenyetuju()
    {
        return $this->belongsTo(User::class, 'id_penyetuju','id');
    }
}
