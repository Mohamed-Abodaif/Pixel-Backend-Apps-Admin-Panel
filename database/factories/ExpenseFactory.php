<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Client;
use App\Models\ClientOrder;
use App\Models\PurchaseInvoice;
use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\ExpenseType;
use App\Models\SystemConfig\PaymentMethod;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
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

            'payment_method_id'=>PaymentMethod::inRandomOrder()->first()->id,

            'currency_id'=>Currency::inRandomOrder()->first()->id,
            'category'=>
                $this->faker->randomElement(
                     [
                        'assets','company_operation','client_po','marketing','client_visits_preorders','purchase_for_inventory'
                     ]
                  )
             ,


            'paid_to'=>$this->faker->name(),
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
             'asset_id' =>Asset::inRandomOrder()->first()->id,
             'client_id' =>Client::inRandomOrder()->first()->id,
             'client_po_id' =>ClientOrder::inRandomOrder()->first()->id,
             'purchase_invoice_id' =>PurchaseInvoice::inRandomOrder()->first()->id,
             'expense_type_id'=>ExpenseType::inRandomOrder()->first()->id,

             'payment_method_id'=>PaymentMethod::inRandomOrder()->first()->id,
             'status'=>	$this->faker->randomElement(
                [
                    'Pending', 'Approved', 'Rejected'
                ]
                ),
            'notes'=>$this->faker->text(),

            'expense_invoice'=>	$this->faker->randomElement(
                [
                    'without_pi', 'with_pi'
                ]
                ),

            'created_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at'=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'user_id'=>User::inRandomOrder()->first()->id
        ];
    }
}
