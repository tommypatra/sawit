<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ram extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function berangkatTimbang()
    {
        return $this->hasMany(BerangkatTimbang::class);
    }

    public function timbangTiket()
    {
        return $this->hasMany(TimbangTiket::class);
    }
}
