<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
    public $timestamps = false;
    protected $primaryKey = 'id_pengumuman';
    public $incrementing = false;
    protected $fillable = [
        'id_pengumuman',
        'judul',
        'data',
        'gambar_header',
        'created_at',
        'updater',
        'postinger',
        'is_posting',
        'tgl_posting'
    ];

    protected $casts = [
        'tgl_posting' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'updater', 'id');
    }
    public function file_pengumuman()
    {
        return $this->belongsTo(Files::class, 'gambar_header', 'id_file');
    }

    public function postinger_user()
    {
        return $this->belongsTo(User::class, 'postinger', 'id');
    }
}
