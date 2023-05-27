<?php

namespace Database\Factories;

use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\CustodySender;
use Illuminate\Database\Eloquent\Factories\Factory;

class BonusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [


            'attachments'=>json_encode([
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

            'notes' => $this->faker->randomHtml(),
            'amount' => $this->faker->randomFloat(),

            'currency_id'=>Currency::inRandomOrder()->first()->id,
            'received_from'=>CustodySender::inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
        ];
    }
}
