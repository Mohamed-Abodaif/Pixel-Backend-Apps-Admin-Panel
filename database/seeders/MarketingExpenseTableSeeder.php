<?php

namespace Database\Seeders;

use App\Models\MarketingExpense;
use Illuminate\Database\Seeder;

class MarketingExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MarketingExpense::factory()->count(200)->create();
    }
}
