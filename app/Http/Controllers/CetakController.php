<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CetakController extends Controller
{
    //
    public function cetakTimbangPabrik()
    {
        return view('cetak/cetak_timbang_pabrik');
    }
}
