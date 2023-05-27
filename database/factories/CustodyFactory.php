<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\WorkSector\SystemConfigurationModels\Currency;
use App\Models\WorkSector\SystemConfigurationModels\CustodySender;

class CustodyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'amount' => $this->faker->randomFloat(),

            'received_from' => CustodySender::inRandomOrder()->first()->id,
            'currency_id' => Currency::inRandomOrder()->first()->id,
            'attachments' => json_encode([
                $this->faker->randomElement(
                    [
                        "house.jpg",
                        "flat.jpg",
                        "apartment.jpg",
                        "room.jpg", "shop.jpg",
                        "lot.jpg", "garage.jpg"
                    ]
                )
            ]),
            'notes' => $this->faker->text(),

            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year')
        ];
    }
}
