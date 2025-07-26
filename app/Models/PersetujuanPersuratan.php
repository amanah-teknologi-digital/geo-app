<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersetujuanPersuratan extends Model
{
    protected $table = 'persetujuan_surat';
    protected $primaryKey = 'id_persetujuan';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'id_persetujuan',
        'id_pengajuan',
        'id_statuspersetujuan',
        'id_akses',
        'id_pihakpenyetuju',
        'penyetuju',
        'nama_penyetuju',
        'keterangan',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function pihakpenyetuju()
    {
        return $this->belongsTo(User::class, 'penyetuju', 'id');
    }
    public function statuspersetujuan()
    {
        return $this->belongsTo(StatusPersetujuan::class, 'id_statuspersetujuan', 'id_statuspersetujuan');
    }

    public function akses()
    {
        return $this->belongsTo(Akses::class, 'id_akses', 'id_akses');
    }

    public function pihakpenyetujupengajuansurat()
    {
        return $this->belongsTo(PihakPenyetujuPengajuanSurat::class, 'id_pihakpenyetuju', 'id_pihakpenyetuju');
    }
}
