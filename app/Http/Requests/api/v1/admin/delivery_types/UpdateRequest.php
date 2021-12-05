<?php

namespace App\Http\Requests\api\v1\admin\delivery_types;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'required|regex:/^[a-zA-Z0-9]+$/u|min:4|max:15',
            'price' => 'required|numeric|between:0.00,9999.99',
            'available' => 'required|boolean',
        ];
    }
}
