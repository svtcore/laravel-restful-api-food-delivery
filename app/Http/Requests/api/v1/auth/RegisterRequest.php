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
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_country_code' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
