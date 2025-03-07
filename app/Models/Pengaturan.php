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
    public function files_geoletter()
    {
        return $this->belongsTo(Files::class, 'file_sop_geoletter', 'id_file');
    }

    public function files_georoom()
    {
        return $this->belongsTo(Files::class, 'file_sop_georoom', 'id_file');
    }

    public function files_geofacility()
    {
        return $this->belongsTo(Files::class, 'file_sop_geofacility', 'id_file');
    }
}
