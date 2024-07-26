<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BerangkatTimbangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $mobil = json_decode($this->input('mobil'), true);
        $this->merge(['mobil' => $mobil]);
    }

    public function rules()
    {
        $rules = [
            'id' => 'nullable',
            'tanggal' => 'required|date_format:Y-m-d',
            'timbang_bersih' => 'required|numeric',
            'timbang_kotor' => 'required|numeric',
            'pabrik_id' => 'required|numeric',
            'operator_id' => 'required|numeric',
            'mobil' => 'required|array',
            'mobil.*.mobil_id' => 'required|numeric',
            'mobil.*.supir_list' => 'required|array',
            'mobil.*.supir_list.*.supir_id' => 'required|numeric',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'id' => 'id berangkat timbang',
            'tanggal' => 'tanggal berangkat timbang',
            'timbang_bersih' => 'timbang bersih (kg)',
            'timbang_kotor' => 'timbang kotor (kg)',
            'pabrik_id' => 'pabrik',
            'operator_id' => 'operator',
            'mobil' => 'mobil',
            'mobil.*.mobil_id' => 'mobil id',
            'mobil.*.supir_list' => 'daftar supir',
            'mobil.*.supir_list.*.supir_id' => 'supir id',
        ];
    }
}
