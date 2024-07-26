<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerangkatTimbang extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function pabrik()
    {
        return $this->belongsTo(Pabrik::class);
    }

    public function berangkatMobil()
    {
        return $this->hasMany(BerangkatMobil::class);
    }

    public function berangkatPabrik()
    {
        return $this->hasOne(BerangkatPabrik::class);
    }
}
