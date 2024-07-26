<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerangkatSupir extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function berangkatMobil()
    {
        return $this->belongsTo(BerangkatMobil::class);
    }

    public function supir()
    {
        return $this->belongsTo(Supir::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
