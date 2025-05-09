<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BerangkatPabrikRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $muatan = json_decode($this->input('muatan'), true);
        $this->merge(['muatan' => $muatan]);
    }

    public function rules()
    {
        $rules = [
            'berangkat_mobil_id' => 'required',
            'tanggal_timbang' => 'required|date_format:Y-m-d',
            'pabrik_timbang_kotor' => 'required|numeric',
            'pabrik_timbang_bersih' => 'required|numeric',
            'tp' => 'required',
            'ram_berat_kotor_awal' => 'required',
            'harga_sawit' => 'required|numeric',
            'muatan' => 'required|array',
            'muatan.*.berangkat_timbang_id' => 'required|numeric',
            'muatan.*.persen' => 'required',
            'muatan.*.harga' => 'required',
            'muatan.*.pabrik_timbang_kotor' => 'required|numeric',
            'muatan.*.pabrik_timbang_bersih' => 'required|numeric',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'berangkat_mobil_id' => 'timbang berangkat mobil',
            'tanggal_timbang' => 'tanggal timbng',
            'pabrik_timbang_kotor' => 'berat kotor timbang pabrik',
            'pabrik_timbang_bersih' => 'berat kotor timbang bersih',
            'ram_berat_kotor_awal' => 'ram berat kotor',
            'tp' => 'persen perhitungan',
            'harga_sawit' => 'harga sawit',
            'muatan' => 'muatan',
            'muatan.*.berangkat_timbang_id' => 'muatan berangkat',
            'muatan.*.persen' => 'persen muatan',
            'muatan.*.harga' => 'harga',
            'muatan.*.pabrik_timbang_kotor' => 'berat kotor bimbang pabrik',
            'muatan.*.pabrik_timbang_bersih' => 'berat bersih bimbang pabrik',
        ];
    }
}
