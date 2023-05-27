<?php

namespace Database\Seeders;

use App\Models\CompanyOperationExpense;
use Illuminate\Database\Seeder;

class CompanyOperationExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyOperationExpense::factory()->count(200)->create();
    }
}