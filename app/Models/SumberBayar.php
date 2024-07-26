<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SumberBayar extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function timbangNota()
    {
        return $this->hasMany(timbangNota::class);
    }
}
