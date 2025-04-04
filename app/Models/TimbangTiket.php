<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimbangTiket extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function ram()
    {
        return $this->belongsTo(Ram::class);
    }


    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function timbangBeli()
    {
        return $this->hasMany(TimbangBeli::class);
    }
}
