<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PengajuanRuangan extends Model
{
    protected $table = 'pengajuan_ruangan';
    public $timestamps = false;
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_pengajuan',
        'pengaju',
        'id_statuspengaju',
        'id_tahapan',
        'pemeriksa_awal',
        'pemeriksa_akhir',
        'nama_kegiatan',
        'deskripsi',
        'tgl_mulai',
        'tgl_selesai',
        'jam_mulai',
        'jam_selesai',
        'nama_pengaju',
        'no_hp',
        'email',
        'email_its',
        'kartu_id',
        'created_at',
        'updated_at',
        'updater'
    ];

    protected $casts = [
        'tgl_mulai'   => 'date',
        'tgl_selesai' => 'date',
        'jam_mulai'   => 'datetime',
        'jam_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Encrypt before saving
    |--------------------------------------------------------------------------
    */
    public function setNoHpAttribute($value)
    {
        $this->attributes['no_hp'] = Crypt::encryptString($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Crypt::encryptString($value);
    }

    public function setEmailItsAttribute($value)
    {
        $this->attributes['email_its'] = Crypt::encryptString($value);
    }

    public function setKartuIdAttribute($value)
    {
        $this->attributes['kartu_id'] = Crypt::encryptString($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Decrypt when reading
    |--------------------------------------------------------------------------
    */
    public function getNoHpAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getEmailAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getEmailItsAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getKartuIdAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function pihakpengaju()
    {
        return $this->belongsTo(User::class, 'pengaju', 'id');
    }

    public function pihakupdater()
    {
        return $this->belongsTo(User::class, 'updater', 'id');
    }

    public function statuspengaju()
    {
        return $this->belongsTo(StatusPengaju::class, 'id_statuspengaju', 'id_statuspengaju');
    }

    public function tahapanpengajuan()
    {
        return $this->belongsTo(TahapanPeminjamanRuangan::class, 'id_tahapan', 'id_tahapan');
    }

    public function persetujuan()
    {
        return $this->hasMany(PersetujuanRuangan::class, 'id_pengajuan', 'id_pengajuan')->orderBy('created_at');
    }

    public function pengajuanruangandetail()
    {
        return $this->hasMany(PengajuanRuanganDetail::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function pengajuanperalatandetail()
    {
        return $this->hasMany(PengajuanPeralatanRuangan::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function surveykepuasan()
    {
        return $this->belongsTo(SurveyKepuasanRuangan::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function pemeriksaawal()
    {
        return $this->belongsTo(User::class, 'pemeriksa_awal', 'id');
    }

    public function pemeriksaakhir()
    {
        return $this->belongsTo(User::class, 'pemeriksa_akhir', 'id');
    }

    public function filepengajuanruangan()
    {
        return $this->hasMany(FilePengajuanRuangan::class, 'id_pengajuan', 'id_pengajuan');
    }

}
