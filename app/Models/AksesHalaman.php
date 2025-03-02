<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AksesHalaman extends Model
{
    protected $table = 'akses_halaman';
    protected $primaryKey = ['id_akses','id_halaman'];
    public $incrementing = false;
    protected $fillable = [
        'id_akses',
        'id_halaman'
    ];

    public function akses()
    {
        return $this->belongsTo(Akses::class,'id_akses','id_akses');
    }
    public function halaman()
    {
        return $this->belongsTo(Halaman::class,'id_halaman','id_halaman');
    }
}
