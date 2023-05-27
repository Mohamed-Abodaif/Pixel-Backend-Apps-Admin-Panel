<?php

namespace Database\Seeders;

use App\Models\SystemConfig\Tender;
use Illuminate\Database\Seeder;

class TenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tender::factory()->count(200)->create();
    }
}
