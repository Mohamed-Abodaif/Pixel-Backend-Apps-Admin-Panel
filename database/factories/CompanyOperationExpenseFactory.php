<?php

namespace Database\Factories;

use App\Models\PurchaseInvoice;
use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\ExpenseType;
use App\Models\SystemConfig\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyOperationExpenseFactory extends Factory
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
            'category'=>
                $this->faker->randomElement(
                     [
                        'assets',
                        'company_operation',
                        'client_po',
                        'marketing',
                        'client_visits_preorders',
                        'purchase_for_inventory',
                        'taxes',
                        'insurances',
                        'exchange_currency'
                     ]
                  )
             ,
             'type'=>	$this->faker->randomElement(
                [
                    'withInvoice', 'withoutInvoice'
                ]
                ),

            'expense_type_id'=>ExpenseType::inRandomOrder()->first()->id,
            'payment_method_id'=>PaymentMethod::inRandomOrder()->first()->id,
            'purchase_invoice_id'=>PurchaseInvoice::inRandomOrder()->first()->id,
            'currency_id'=>Currency::inRandomOrder()->first()->id,
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
