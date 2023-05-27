<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'payment_date'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'electronic_sales_invoice_number' => $this->faker->randomDigit(),
            'sales_invoice_number' => $this->faker->randomDigit(),
            //
            'paid_value'=>$this->faker->randomFloat(),
            'rest_value'=>$this->faker->randomFloat(),
            'invoice_status'=>$this->faker->randomElement( ['draft', 'approved internally','sent','approved in portal','due','overdue','cancelled','closed']),
            'payment_status'=>$this->faker->randomElement( ['not paid', 'partially paid','paid','refunded']),

            'invoice_net_value'=>$this->faker->randomFloat(),
            'client_id'=>Client::inRandomOrder()->first()->id,
            'department_id'=>Department::inRandomOrder()->first()->id,
            'currency_id'=>Currency::inRandomOrder()->first()->id,
            'invoice_value_without_taxes'=>$this->faker->randomFloat(),
            'invoice_sales_taxes_value'	=>$this->faker->randomFloat(),
            'invoice_add_and_discount_taxes_value'=>$this->faker->randomFloat(),
            'invoice_attachments'=>$this->faker->randomElement([
                'company_operation.jpg',
                'client_po.jpg',
                'marketing.jpg',
                'client_visits_preorders.jpg',
                'purchase_for_inventory.jpg',
                'taxes.jpg',
                'insurances.jpg',
                'exchange_currency.png'
            ]),
            'notes'=>$this->faker->text(),
            'status'=>$this->faker->randomElement(['Draft', 'Sent', 'Paid', 'Partially Paid']),
            'created_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year')
        ];
    }
}
