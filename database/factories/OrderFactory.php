<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            #user_id
            #payment_type_id
            #discount_id
            #status_id
            'total_cost' => $this->faker->randomFloat(1, 100, 3000),
            'comment' => $this->faker->text(100),   
        ];
    }
}
