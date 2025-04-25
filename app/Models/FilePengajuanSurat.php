<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilePengajuanSurat extends Model
{
    protected $table = 'file_pengajuansurat';
    protected $primaryKey = ['id_pengajuan', 'id_file'];
    public $incrementing = false;
    protected $fillable = [
        'id_pengajuan',
        'id_file'
    ];

    public $timestamps = false;
}
