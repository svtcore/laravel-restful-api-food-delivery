<?php

namespace App\Http\Requests\api\v1\restaurants;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'key' => 'required|regex:/^[a-zA-Z0-9.,-]+$/u|min:4|max:15',
        ];
    }
}
