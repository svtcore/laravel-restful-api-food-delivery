<?php

namespace App\Http\Requests\api\v1\admin\restaurants;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
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
            'restaurant_id' => '',
            'city_id' => '',
            'street_type_id' => '',
            'street_name' => '',
            'building_number' => '',
        ];
    }
}
