<?php

namespace Database\Seeders;

use App\Models\ClientPoExpense;
use Illuminate\Database\Seeder;

class ClientPoExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientPoExpense::factory()->count(200)->create();
    }
}
