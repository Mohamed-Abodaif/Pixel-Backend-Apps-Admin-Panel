<?php

namespace Database\Seeders;

use App\Models\InsurancType;
use Illuminate\Database\Seeder;

class InsurancTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InsurancType::factory()->count(200)->create();
    }
}
