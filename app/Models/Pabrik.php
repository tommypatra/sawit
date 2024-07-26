<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pabrik extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function userPabrik()
    {
        return $this->hasMany(UserPabrik::class);
    }

    public function berangkatTimbang()
    {
        return $this->hasMany(BerangkatTimbang::class);
    }
}
