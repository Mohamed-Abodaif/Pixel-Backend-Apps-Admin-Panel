<?php

namespace Modules\Country\Database\factories;

use App\Models\WorkSector\CountryModule\Area;
use App\Models\WorkSector\CountryModule\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Area::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'city_id' => City::factory()
        ];
    }
}
