<?php

namespace Modules\Country\Database\factories;

use App\Models\WorkSector\CountryModule\City;
use App\Models\WorkSector\CountryModule\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->state,
            'country_id' => Country::factory()
        ];
    }
}
