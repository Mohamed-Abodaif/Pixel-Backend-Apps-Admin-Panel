<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
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
            'status'=>$this->faker->randomElement([1,0]),
            'type'=>$this->faker->randomElement(['FREE ZONE', 'LOCAL', 'INTERNATIONAL', 'NOT SPECIFIED']),
            'registration_no'=>$this->faker->unique()->numberBetween(1000000, 100000000000000),
            'taxes_no'=>$this->faker->unique()->numberBetween(1000000, 100000000000000),
            'registration_no_attachment'=>json_encode([
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
            
             'taxes_no_attachment'=>json_encode([
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
             'exemption_attachment'=>json_encode([
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

             'sales_taxes_attachment'=>json_encode([
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
             'logo'=> $this->faker->randomElement(['assets.jpg',
             'company_operation.jpg',
             'client_po.jpg',
             'marketing.jpg',
             'client_visits_preorders.jpg',
             'purchase_for_inventory.jpg',
             'taxes.jpg',
             'insurances.jpg',
             'exchange_currency.jpg',]),
             
            'notes'=>$this->faker->text(),
            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
        ];

    }
}
