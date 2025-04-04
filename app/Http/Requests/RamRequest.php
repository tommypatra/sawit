<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RamRequest extends FormRequest
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

    public function rules()
    {
        $rules = [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'is_aktif' => 'required|integer',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'nama' => 'nama RAM',
            'alamat' => 'alamat lengkap',
            'is_aktif' => 'status aktif',
        ];
    }
}
