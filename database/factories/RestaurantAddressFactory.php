<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            #restaurant_id
            #city_id
            #street_type_id
            'street_name' => $this->faker->streetName,
            'building_number' => strtoupper($this->faker->bothify('##?')),
        ];
    }
}
