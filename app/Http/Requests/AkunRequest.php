<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AkunRequest extends FormRequest
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
        // dd($this->all());
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'hp' => 'nullable',
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:8';
        } elseif ($this->isMethod('put')) {
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'nama',
            'email' => 'email pengguna',
            'alamat' => 'alamat',
            'hp' => 'nomor hp',
            'password' => 'kata sandi',
        ];
    }
}
