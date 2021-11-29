<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->monthName($max = 'now'),
            'code' => strtoupper($this->faker->bothify('###??')),
            'amount' => $this->faker->numberBetween(10, 999),
            'value' => $this->faker->numberBetween(1, 100),
            'expired' => $this->faker->dateTimeBetween('now', '+31 days'),
        ];
    }
}
