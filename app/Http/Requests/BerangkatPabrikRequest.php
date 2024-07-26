<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BerangkatPabrikRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'id' => 'nullable',
            'berangkat_timbang_id' => 'required|numeric',
            'tanggal' => 'required|date_format:Y-m-d',
            'timbang_bersih' => 'required|numeric',
            'timbang_kotor' => 'required|numeric',
            'harga_sawit' => 'required|numeric',
            'sewa_supir' => 'required|numeric',
            'sewa_mobil' => 'required|numeric',
            'biaya_muat' => 'required|numeric',
            'biaya_bongkar' => 'required|numeric',
            'total_sawit' => 'required|numeric',
            'total_masuk' => 'required|numeric',
            'total_keluar' => 'required|numeric',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'id' => 'id bayar pabrik',
            'berangkat_timbang_id' => 'id timbang berangkat',
            'tanggal' => 'tanggal',
            'timbang_bersih' => 'timbang bersih',
            'timbang_kotor' => 'timbang kotor',
            'harga_sawit' => 'harga sawit',
            'sewa_supir' => 'sewa supir',
            'sewa_mobil' => 'sewa mobil',
            'biaya_muat' => 'biaya muat',
            'biaya_bongkar' => 'biaya bonkar',
            'total_sawti' => 'total harga sawit',
            'total_masuk' => 'total masuk',
            'total_keluar' => 'total keluar',
        ];
    }
}
