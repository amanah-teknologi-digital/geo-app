<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table = 'files';
    protected $primaryKey = 'id_file';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_file',
        'file_name',
        'location',
        'mime',
        'ext',
        'file_size',
        'created_at'
    ];
}
