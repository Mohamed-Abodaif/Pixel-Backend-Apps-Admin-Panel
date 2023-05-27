<?php

namespace Database\Factories;

use App\Models\WorkSector\UsersModule\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustodySenderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'status' => $this->faker->randomElement([1, 0]),
            'user_id' => User::inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
        ];
    }
}
