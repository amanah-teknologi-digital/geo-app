<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Halaman extends Model
{
    protected $table = 'halaman';
    protected $primaryKey = 'id_halaman';
    protected $fillable = [
        'id_header',
        'name',
        'slug',
        'url'
    ];
}
