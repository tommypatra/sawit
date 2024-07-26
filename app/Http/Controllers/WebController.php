<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    //dashboard
    public function auth()
    {
        return view('auth');
    }

    //dashboard
    public function dashboard()
    {
        return view('akun/dashboard');
    }

    //tiket timbang
    public function timbangTiket()
    {
        return view('akun/timbang_tiket');
    }

    //tiket berangkat
    public function timbangBerangkat()
    {
        return view('akun/timbang_berangkat');
    }
}
