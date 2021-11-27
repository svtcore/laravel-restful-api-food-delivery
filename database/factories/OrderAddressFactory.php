<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            #order_id
            #city_id
            #street_type_id
            'street_name' => $this->faker->streetName,
            'building_number' => strtoupper($this->faker->bothify('##?')),
            'entrace' => $this->faker->numberBetween(1, 5),
            'access_code' => strtoupper($this->faker->bothify('####')),
            'floor' => $this->faker->numberBetween(1, 9),
            'apartment' => $this->faker->numberBetween(1, 250),
        ];
    }
}
