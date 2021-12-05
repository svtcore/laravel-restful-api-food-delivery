<?php

namespace App\Http\Requests\api\v1\admin\restaurants;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateRequest extends FormRequest
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
            'city_id' => 'required|numeric|max:9999999999',
            'street_type_id' => 'required|numeric|max:9999999999',
            'street_name' => 'required|regex:/^[a-zA-Z0-9- ]+$/u|min:2|max:50',
            'building_number' => 'required|regex:/^[a-zA-Z0-9-]+$/u|min:1|max:5',
        ];
    }
}
