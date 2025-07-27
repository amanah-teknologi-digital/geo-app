<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AksesUser extends Model
{
    protected static function boot(){
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id_akses', 'ASC'); // Urutkan default
        });
    }

    protected $table = 'akses_user';
    protected $primaryKey = ['id_akses','id_user'];
    public $incrementing = false;
    protected $fillable = [
        'id_akses',
        'id_user',
        'is_default',
        'created_at',
        'updated_at'
    ];

    public function akses()
    {
        return $this->belongsTo(Akses::class,'id_akses','id_akses');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'id_user','id');
    }
}
