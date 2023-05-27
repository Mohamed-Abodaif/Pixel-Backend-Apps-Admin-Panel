<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Client;
use App\Models\ClientOrder;
use App\Models\PurchaseInvoice;
use App\Models\SystemConfig\Currency;
use App\Models\SystemConfig\PaymentMethod;
use App\Models\SystemConfig\Tender;
use Illuminate\Database\Eloquent\Factories\Factory;

class InsuranceExpenseFactory extends Factory
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
            'asset_id'=>Asset::inRandomOrder()->first()->id,
            'insurance_duration'=>ClientOrder::inRandomOrder()->first()->id,
            'months_list'=>Client::inRandomOrder()->first()->id,
            "insurance_refrence_number"=>$this->faker->unique()->numberBetween(1000000, 10000000000000),
            "tender_insurance_percentage"=>$this->faker->unique()->numberBetween(100000, 10000000000),
            "tender_estimated_date"=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            "final_refund_date"=>$this->faker->dateTimeBetween('-3 year', '+3 year'),
            'type'=>  $this->faker->randomElement(['SocialInsurance','MedicalInsurance','AssetInsurance','TenderInsurance','OtherInsurance']),
            "tender_insurance_type"=>$this->faker->randomElement(['InitialInsurance', 'FinalInsurance']),
            'currency_id'=>Currency::inRandomOrder()->first()->id,
            'tender_id'=>Tender::inRandomOrder()->first()->id,
            'client_id'=>Client::inRandomOrder()->first()->id,
            'asset_id'=>Asset::inRandomOrder()->first()->id,
            'purchase_invoice_id'=>PurchaseInvoice::inRandomOrder()->first()->id,

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
