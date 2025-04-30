<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPengaju extends Model
{
    protected $table = 'status_pengaju';
    protected $primaryKey = 'id_statuspengaju';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id_statuspengaju',
        'nama'
    ];
}
