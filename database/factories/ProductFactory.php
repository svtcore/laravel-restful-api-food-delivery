<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
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
            #category_id
            'name' => $this->faker->state,
            'price' => $this->faker->randomFloat(1, 50, 999),
            'weight' => $this->faker->numberBetween(50, 10000),
            'description' => $this->faker->text(100),
            'available' => $this->faker->boolean,
        ];
    }
}
