<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryTypeFactory extends Factory
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
            'name' => $this->faker->randomElement($array = array ('pickup','delivery')),
            'price' => $this->faker->randomFloat(1, 30, 150),
            'available' => $this->faker->boolean,
        ];
    }
}
