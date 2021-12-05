<?php

namespace App\Http\Requests\api\v1\auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|regex:/^[a-zA-Z0-9-]+$/u|min:2|max:50',
            'last_name' => 'required|regex:/^[a-zA-Z0-9-]+$/u|min:2|max:50',
            'phone_country_code' => 'required|numeric|max:99999999',
            'phone_number' => 'required|numeric|max:99999999',
            'email' => 'required|email',
            'password' => 'required|string|regex:/^[a-zA-Z0-9@$!%*\/#?&.]+$/u|min:8|max:100',
        ];
    }
}
