<?php

namespace Database\Factories;

use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExchangeExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'exchange_date'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'from_amount'=>$this->faker->randomFloat(),
            'to_amount'=>$this->faker->randomFloat(),
            'payment_method_id'=>PaymentMethod::inRandomOrder()->first()->id,

            'to_currency'=>Currency::inRandomOrder()->first()->id,
            'from_currency'=>Currency::inRandomOrder()->first()->id,

            'exchange_rate'=>$this->faker->numberBetween([1,100]),
            'exchange_place'=>$this->faker->randomFloat(),
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
            'notes'=>$this->faker->text(),

                'created_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
                'updated_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year')
        ];
    }
}
