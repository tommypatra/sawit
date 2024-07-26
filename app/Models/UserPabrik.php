<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPabrik extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pabrik()
    {
        return $this->belongsTo(Pabrik::class);
    }

    public function grup()
    {
        return $this->belongsTo(Grup::class);
    }
}
