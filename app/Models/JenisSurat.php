<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';
    protected $primaryKey = 'id_jenissurat';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id_jenissurat',
        'nama',
        'default_form',
        'created_at',
        'updated_at',
        'updater'
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updater', 'id');
    }
}
