<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPersetujuan extends Model
{
    protected $table = 'status_persetujuan';
    protected $primaryKey = 'id_statuspersetujuan';
    public $incrementing = false;
    protected $fillable = [
        'id_statuspersetujuan',
        'nama',
        'class_label',
        'class_bg'
    ];
}
