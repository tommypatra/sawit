<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimbangNota extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function sumberBayar()
    {
        return $this->belongsTo(SumberBayar::class);
    }

    public function timbangBeli()
    {
        return $this->hasMany(TimbangBeli::class);
    }
}
