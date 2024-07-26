<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grup()
    {
        return $this->belongsTo(Grup::class);
    }

    public function timbangTiket()
    {
        return $this->hasMany(TimbangTiket::class);
    }

    public function timbangNota()
    {
        return $this->hasMany(TimbangNota::class);
    }

    public function berangkatTimbang()
    {
        return $this->hasMany(BerangkatTimbang::class);
    }

    public function berangkatMobil()
    {
        return $this->hasMany(BerangkatMobil::class);
    }

    public function berangkatSupir()
    {
        return $this->hasMany(BerangkatSupir::class);
    }

    public function berangkatPabrik()
    {
        return $this->hasMany(BerangkatPabrik::class);
    }
}
