<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimbangTiketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'tanggal' => 'required|date_format:Y-m-d',
            'timbang_bersih' => 'required|numeric',
            'ram_id' => 'required',
            'pelanggan_id' => 'required|integer',
            'operator_id' => 'required|integer',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'ram_id' => 'lokasi RAM',
            'tanggal' => 'tanggal tiket timbang',
            'timbang_bersih' => 'timbang bersih',
            'pelanggan_id' => 'pelanggan',
            'operator_id' => 'operator',
            'file' => 'file',
        ];
    }
}
