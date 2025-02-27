<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akses extends Model
{
    protected $table = 'akses';
    protected $primaryKey = 'id_akses';
    public $incrementing = false;
    protected $fillable = [
        'id_akses',
        'nama',
        'id_halaman'
    ];

    public function halaman()
    {
        return $this->belongsTo(Halaman::class,'id_halaman','id_halaman');
    }
}
