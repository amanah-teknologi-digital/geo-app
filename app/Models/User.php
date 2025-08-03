<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_its',
        'password',
        'kartu_id',
        'no_hp',
        'file_kartuid',
        'id_akses'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Crypt::encryptString($value);
        $this->attributes['email_hash'] = hash('sha256', strtolower($value));
    }

    public function setEmailItsAttribute($value)
    {
        $this->attributes['email_its'] = $value !== null ? Crypt::encryptString($value) : null;
        $this->attributes['email_its_hash'] = $value !== null ? hash('sha256', strtolower($value)) : null;
    }

    public function setKartuIdAttribute($value)
    {
        $this->attributes['kartu_id'] = Crypt::encryptString($value);
        $this->attributes['kartu_id_hash'] = hash('sha256', $value);
    }

    public function setNoHpAttribute($value)
    {
        $this->attributes['no_hp'] = Crypt::encryptString($value);
        $this->attributes['no_hp_hash'] = hash('sha256', $value);
    }

    public function getEmailAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getEmailItsAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }

    public function getKartuIdAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getNoHpAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function aksesuser()
    {
        return $this->hasMany(AksesUser::class,'id_user','id');
    }

    public function files()
    {
        return $this->belongsTo(Files::class,'file_kartuid','id_file');
    }
}
