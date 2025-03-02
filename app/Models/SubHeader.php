<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubHeader extends Model
{
    protected $table = 'sub_header';
    protected $primaryKey = 'id_subheader';
    public $incrementing = false;
    protected $fillable = [
        'name',
        'icon',
        'slug'
    ];
}
