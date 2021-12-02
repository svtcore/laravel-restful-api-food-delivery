<?php

namespace App\Http\Requests\api\v1\admin\orders;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'phone_country_code' =>  'required',
            'phone_number' => 'required',
            'payment_type_id' => 'required',
            'discount_id' => 'nullable',
            'comment' => 'nullable',
            'city_id' => 'required',
            'street_type_id' => 'required',
            'street_name' => 'required',
            'building_number' => 'required',
            'entrace' => 'nullable',
            'access_code' => 'nullable',
            'floor' => 'nullable',
            'apartment' => 'nullable',
            'products' => 'required',
        ];
    }
}
