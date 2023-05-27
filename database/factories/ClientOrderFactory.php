<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\Department;
use App\Models\SystemConfig\PaymentTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'order_name'=>$this->faker->name(),
        'date' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
        'delivery_date'=> $this->faker->dateTimeBetween('-3 year', '+3 year'),
        'client_id'=>Client::inRandomOrder()->first()->id,
        'order_number'=>$this->faker->unique()->numberBetween(1, 10000),
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
        'status'=>$this->faker->randomElement([
            'In Progress', 'Delayed', 'Invoiced','Good Receive'
        ]),
            'created_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year')
        ];
    }
}
