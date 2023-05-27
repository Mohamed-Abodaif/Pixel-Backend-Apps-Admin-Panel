<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
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
            'billing_address'=>$this->faker->text(),
            'type'=>
                $this->faker->randomElement(
                    ['FREE ZONE', 'LOCAL', 'INTERNATIONAL','NOT SPECIFIED']
                  )
             ,
            
            'country' => $this->faker->text(),
            // 'amount' => $this->faker->randomFloat(),
            
            'exemption_attachment' => $this->faker->randomElement([
                'assets.jpg',
                'company_operation.jpg',
                'client_po.jpg',
                'marketing.jpg',
                'client_visits_preorders.jpg',
                'purchase_for_inventory.jpg',
                'taxes.jpg',
                'insurances.jpg',
                'exchange_currency.jpg',
                
            ]),

            'sales_taxes_attachment' => $this->faker->randomElement([
                'assets.jpg',
                'company_operation.jpg',
                'client_po.jpg',
                'marketing.jpg',
                'client_visits_preorders.jpg',
                'purchase_for_inventory.jpg',
                'taxes.jpg',
                'insurances.jpg',
                'exchange_currency.jpg',
                
            ]),
            
            'logo' => "default.jpg",
            'notes'=>$this->faker->text(),
            'notes'=>$this->faker->text(),
            'status'=>$this->faker->randomElement([0,1]),
            'country_id'=>Country::inRandomOrder()->first()->id,
            'taxes_no' => $this->faker->randomDigit(),
            'taxes_no_attachment' => $this->faker->randomElement([
                'assets.jpg',
                'company_operation.jpg',
                'client_po.jpg',
                'marketing.jpg',
                'client_visits_preorders.jpg',
                'purchase_for_inventory.jpg',
                'taxes.jpg',
                'insurances.jpg',
                'exchange_currency.jpg',
                
            ]),
            'registration_no' => $this->faker->randomDigit(),
            'registration_no_attachment' => $this->faker->randomElement([
                'assets.jpg',
                'company_operation.jpg',
                'client_po.jpg',
                'marketing.jpg',
                'client_visits_preorders.jpg',
                'purchase_for_inventory.jpg',
                'taxes.jpg',
                'insurances.jpg',
                'exchange_currency.jpg',
                
            ]),
            

            
            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            
        ];
    }
}
