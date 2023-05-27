<?php

namespace Database\Seeders;

use App\Models\TaxExpense;
use Illuminate\Database\Seeder;

class TaxExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxExpense::factory()->count(200)->create();
    }
}
