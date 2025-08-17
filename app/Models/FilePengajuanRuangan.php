<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilePengajuanRuangan extends Model
{
    protected $table = 'file_pengajuanruang';
    public $timestamps = false;
    protected $primaryKey = 'id_file';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_pengajuan',
        'id_file'
    ];

    public function file()
    {
        return $this->belongsTo(Files::class, 'id_file', 'id_file');
    }
}
