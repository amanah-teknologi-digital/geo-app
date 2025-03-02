<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    protected $table = 'header';
    protected $primaryKey = 'id_header';
    public $incrementing = false;
    protected $fillable = [
        'nama'
    ];
}
