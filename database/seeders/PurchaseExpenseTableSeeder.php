<?php

namespace Database\Seeders;

use App\Models\PurchaseExpense;
use Illuminate\Database\Seeder;

class PurchaseExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PurchaseExpense::factory()->count(200)->create();
    }
}
