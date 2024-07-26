<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grup extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function admin()
    {
        return $this->hasMany(Admin::class);
    }

    public function operator()
    {
        return $this->hasMany(Operator::class);
    }

    public function supir()
    {
        return $this->hasMany(Supir::class);
    }

    public function userPabrik()
    {
        return $this->hasMany(UserPabrik::class);
    }
}
