<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPabrikRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'lokasi RAM',
            'email' => 'email pengguna',
        ];
    }
}
