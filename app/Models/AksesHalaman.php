<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AksesHalaman extends Model
{
    protected static function boot(){
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id_akses', 'ASC')->orderBy('urutan', 'ASC'); // Urutkan default berdasarkan tanggal terbaru
        });
    }

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
