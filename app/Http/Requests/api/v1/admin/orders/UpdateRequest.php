<?php

namespace App\Http\Requests\api\v1\admin\orders;

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
            'status_id' => 'required|numeric|max:9999999999',
            'first_name' => 'required|regex:/^[a-zA-Z-]+$/u|min:2|max:50',
            'phone_country_code' =>  'required|numeric|max:9999999999',
            'phone_number' => 'required|numeric|max:9999999999',
            'payment_type_id' => 'required|numeric|max:9999999999',
            'discount_id' => 'nullable|numeric|max:9999999999',
            'comment' => 'nullable|regex:/^[a-zA-Z-,.+= ]+$/u|min:2|max:2000',
            'city_id' => 'required|numeric|max:9999999999',
            'street_type_id' => 'required|numeric|max:9999999999',
            'street_name' => 'required|regex:/^[a-zA-Z0-9-]+$/u|min:2|max:50',
            'building_number' => 'required|regex:/^[a-zA-Z0-9-]+$/u|min:1|max:5',
            'entrace' => 'nullable|regex:/^[a-zA-Z0-9-]+$/u|min:1|max:5',
            'access_code' => 'nullable|regex:/^[a-zA-Z0-9-.,%#]+$/u|min:1|max:10',
            'floor' => 'nullable|regex:/^[a-zA-Z0-9-]+$/u|min:1|max:10',
            'apartment' => 'nullable|regex:/^[a-zA-Z0-9-]+$/u|min:1|max:10',
            'products' => 'required|regex:/^[0-9:{},"\' ]+$/u|min:1|max:2000',
        ];
    }
}
