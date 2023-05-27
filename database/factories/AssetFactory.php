<?php

namespace Database\Factories;
use App\Models\SystemConfig\AssetsCategory;
use App\Models\SystemConfig\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'asset_name' => $this->faker->name(),
            'asset_description' => $this->faker->text(1000),
            'notes' => $this->faker->text(1000),
            'picture' => $this->faker->imageUrl(640, 480, 'animals', true),
            'buying_date' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'department_id' =>  Department::inRandomOrder()->first()->id,
            'asset_category_id' => AssetsCategory::inRandomOrder()->first()->id,
            'status' => "Available",
            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),


        ];
    }
}
