<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';
    protected $primaryKey = 'updater';
    public $incrementing = false;
    protected $fillable = [
        'alamat',
        'admin_geoletter',
        'admin_ruang',
        'admin_peralatan',
        'link_yt',
        'link_fb',
        'link_ig',
        'link_linkedin',
        'updater'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'updater', 'id');
    }
}
