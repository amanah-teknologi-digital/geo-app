<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPengajuan extends Model
{
    protected $table = 'status_pengajuan';
    protected $primaryKey = 'id_statuspengajuan';
    public $incrementing = false;
    protected $fillable = [
        'id_statuspengajuan',
        'nama',
        'html_color'
    ];
}
