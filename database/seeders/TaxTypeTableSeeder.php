<?php

namespace Database\Seeders;

use App\Models\SystemConfig\TaxType;
use Illuminate\Database\Seeder;

class TaxTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxType::factory()->count(200)->create();
    }
}
