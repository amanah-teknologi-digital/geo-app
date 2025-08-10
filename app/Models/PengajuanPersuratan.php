<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PengajuanPersuratan extends Model
{
    protected $table = 'pengajuan_surat';
    public $timestamps = false;
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_pengajuan',
        'pengaju',
        'id_statuspengajuan',
        'id_jenissurat',
        'nama_pengaju',
        'no_hp',
        'email',
        'email_its',
        'kartu_id',
        'created_at',
        'keterangan',
        'data_form',
        'user_perevisi',
        'nama_pendukung',
        'id_datapendukung',
        'updated_at',
        'updater'
    ];

    protected $casts = [
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

    public function setDataFormAttribute($value)
    {
        $this->attributes['data_form'] = Crypt::encryptString($value);
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

    public function getDataFormAttribute($value)
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

    public function jenis_surat()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenissurat', 'id_jenissurat');
    }

    public function statuspengajuan()
    {
        return $this->belongsTo(StatusPengajuan::class, 'id_statuspengajuan', 'id_statuspengajuan');
    }

    public function persetujuan()
    {
        return $this->hasMany(PersetujuanPersuratan::class, 'id_pengajuan', 'id_pengajuan')->orderBy('created_at');
    }

    public function filesurat()
    {
        return $this->hasMany(FilePengajuanSurat::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function pihakpenyetuju()
    {
        return $this->hasMany(PihakPenyetujuPengajuanSurat::class, 'id_pengajuan', 'id_pengajuan')->orderBy('urutan');
    }

    public function filependukung()
    {
        return $this->belongsTo(Files::class, 'id_datapendukung', 'id_file');
    }

    public function surveykepuasan()
    {
        return $this->belongsTo(SurveyKepuasanPersuratan::class, 'id_pengajuan', 'id_pengajuan');
    }
}
