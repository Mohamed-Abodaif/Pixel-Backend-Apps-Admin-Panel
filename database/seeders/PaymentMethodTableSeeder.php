<?php

namespace Database\Seeders;

use App\Models\SystemConfig\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::factory()->count(200)->create();
    }
}
