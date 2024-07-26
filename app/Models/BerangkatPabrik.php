<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerangkatPabrik extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function berangkatTimbang()
    {
        return $this->belongsTo(BerangkatTimbang::class);
    }
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
