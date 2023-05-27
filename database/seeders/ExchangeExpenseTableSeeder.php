<?php

namespace Database\Seeders;

use App\Models\ExchangeExpense;
use Illuminate\Database\Seeder;

class ExchangeExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExchangeExpense::factory()->count(200)->create();
    }
}
