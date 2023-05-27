<?php

namespace Database\Seeders;
use App\Models\AssetExpense;
use Illuminate\Database\Seeder;

class AssetExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssetExpense::factory()->count(200)->create();
    }
}
