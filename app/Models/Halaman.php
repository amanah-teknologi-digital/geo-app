<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function header()
    {
        return $this->belongsTo(Header::class,'id_header','id_header');
    }

    public function subheader()
    {
        return $this->belongsTo(SubHeader::class,'id_subheader','id_subheader');
    }
}
