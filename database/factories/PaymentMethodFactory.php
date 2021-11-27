<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement($array = array ('Cash','Credit Card','Bonus Card', 'Google Pay', 'Apple Pay', 'Bitcoin', 'Dogecoin')),
            'available' => $this->faker->boolean,
        ];
    }
}
