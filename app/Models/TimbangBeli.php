<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimbangBeli extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function timbangTiket()
    {
        return $this->belongsTo(TimbangTiket::class);
    }

    public function timbangNota()
    {
        return $this->belongsTo(TimbangNota::class);
    }
}
