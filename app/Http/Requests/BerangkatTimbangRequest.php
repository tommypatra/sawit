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
        $muatan = json_decode($this->input('muatan'), true);
        $this->merge(['muatan' => $muatan]);
    }

    public function rules()
    {
        $rules = [
            'id' => 'nullable',
            'tanggal' => 'required|date_format:Y-m-d',
            'pabrik_id' => 'required|numeric',
            'operator_id' => 'required|numeric',
            'mobil_id' => 'required|numeric',
            'supir_id' => 'required|numeric',
            'muatan' => 'required|array',
            'muatan.*.ram_id' => 'required|numeric',
            'muatan.*.ram_timbang_kotor' => 'required|numeric',
            'muatan.*.ram_timbang_bersih' => 'required|numeric',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'id' => 'id berangkat',
            'tanggal' => 'tanggal berngkat',
            'pabrik_id' => 'pabrik tujuan',
            'operator_id' => 'operator',
            'mobil_id' => 'mobil yang digunakan',
            'supir_id' => 'supir mobil',
            'muatan' => 'muatan',
            'muatan.*.ram_id' => 'ram',
            'muatan.*.ram_timbang_kotor' => 'timbang kotor',
            'muatan.*.ram_timbang_bersih' => 'timbang bersih',
        ];
    }
}
