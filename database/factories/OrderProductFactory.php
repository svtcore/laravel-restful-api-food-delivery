<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderProductFactory extends Factory
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
            #product_id
            'amount' => $this->faker->numberBetween(1, 50),
        ];
    }
}
