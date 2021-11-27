<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'working_time_start' => $this->faker->time($format = 'H:i:s', $max = 'now'),
            'working_time_end' => $this->faker->time($format = 'H:i:s', $max = 'now'),
            'working_day_start' => $this->faker->dayOfWeek($max = 'now'),
            'working_day_end' => $this->faker->dayOfWeek($max = 'now'),
            'description' => $this->faker->text(150),
        ];
    }
}
