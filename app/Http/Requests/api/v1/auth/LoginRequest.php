<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone_country_code' => 'required|numeric|max:9999999999',
            'phone_number' => 'required|numeric|max:9999999999',
            'password' => 'required|string|regex:/^[a-zA-Z0-9@$!%*\/#?&.]+$/u|min:8|max:100',
            "client_id"     => 'required|numeric|max:9999999999',
            "client_secret" => 'required|regex:/^[a-zA-Z0-9-]+$/u|min:4|max:100',
        ];
    }
}
