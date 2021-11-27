<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           'name' => $this->faker->randomElement($array = array ('Dishes', 'Drinks', 'Dessert', 'Fast Food', 'Pizza', 'Sushi')),
        ];
    }
}
