<?php

namespace Database\Seeders;
use App\Models\SystemConfig\ExpenseType;
use Illuminate\Database\Seeder;

class ExpenseTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenseType::factory(200)->create();
    }
}
