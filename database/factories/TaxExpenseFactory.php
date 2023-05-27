<?php

namespace Database\Factories;

use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\PaymentMethod;
use App\Models\SystemConfig\TaxType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payment_date'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'amount'=>$this->faker->randomFloat(),
            'paid_to'=> $this->faker->text(),


            'years_list'=> $this->faker->randomElement(
                [
                   '2018','2019','2020','2021','2022','2023','2024'
                ]
             ),
             'months_list'=> $this->faker->randomElement(
                [
                   'jan','feb','mach','april','may','june','july','augsto','september','october','november','december'
                ]
             ),
             'tax_percentage'=>$this->faker->numberBetween(1,10),
            'type'=>  $this->faker->randomElement(['IncomeTaxes', 'OtherTaxes']),
            'tax_type_id'=>  TaxType::inRandomOrder()->first()->id,
            'currency_id'=>Currency::inRandomOrder()->first()->id,

            'payment_method_id'=>PaymentMethod::inRandomOrder()->first()->id,
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
