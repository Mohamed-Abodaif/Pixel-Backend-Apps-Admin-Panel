<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\Department;
use App\Models\SystemConfig\PaymentTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientQuotationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'due_date'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'quotation_number'=>$this->faker->unique()->numberBetween(1, 10000),
            'quotation_name'=>$this->faker->name(),
            'client_id'=>Client::inRandomOrder()->first()->id,
            'department_id'=>Department::inRandomOrder()->first()->id,
            'payments_terms_id'=>PaymentTerm::inRandomOrder()->first()->id,
            'currency_id'=>Currency::inRandomOrder()->first()->id,
            'notes'=>$this->faker->text(),
            'quotation_net_value'=>$this->faker->randomFloat(),
            'status'=>$this->faker->randomElement([
                'Draft', 'Sent', 'Get PO', 'No PO'
            ]),
                'created_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
                'updated_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year')
        ];
    }
}
