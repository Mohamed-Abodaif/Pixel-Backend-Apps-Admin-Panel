<?php

namespace Database\Factories;

use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\Department;
use App\Models\SystemConfig\PaymentTerm;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_name' => $this->faker->name(),
            'date'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'delivery_date'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'vendor_id' =>  Vendor::inRandomOrder()->first()->id,
            'order_number' => $this->faker->numberBetween(10000,10000000000000000000),
            'department_id'=>Department::inRandomOrder()->first()->id,
            'payments_terms_id'=>PaymentTerm::inRandomOrder()->first()->id,
            'currency_id'=>Currency::inRandomOrder()->first()->id,
            'po_total_value'=>$this->faker->randomFloat(),
            'po_sales_taxes_value'=>$this->faker->randomFloat(),
            'po_add_and_discount_taxes_value'=>$this->faker->randomFloat(),
            'po_attachments'=>json_encode([
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
            'status'=>$this->faker->randomElement(['In Progress','Delayed','Invoiced','Good Receive']),
            'created_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year')
        ];
    }
}
