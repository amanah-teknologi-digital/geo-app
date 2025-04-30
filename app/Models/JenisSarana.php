<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSarana extends Model
{
    protected $table = 'jenis_sarana';
    protected $primaryKey = 'id_jenissarana';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id_jenissarana',
        'nama'
    ];
}
