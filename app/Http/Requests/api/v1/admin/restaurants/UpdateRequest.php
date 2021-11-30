<?php

namespace App\Http\Requests\api\v1\admin\restaurants;

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
            'name' => '',
            'working_time_start' => '',
            'working_time_end' => '',
            'working_day_start' => '',
            'working_day_end' => '',
            'description' => '',
            'address_id' => '',
            'city_id' => '',
            'street_type_id' => '',
            'street_name' => '',
            'building_number' => '',
        ];
    }
}
