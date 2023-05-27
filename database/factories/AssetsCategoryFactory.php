<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetsCategoryFactory extends Factory
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
            'status'=>$this->faker->randomElement([0,1]),

            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),

        ];
    }
}
