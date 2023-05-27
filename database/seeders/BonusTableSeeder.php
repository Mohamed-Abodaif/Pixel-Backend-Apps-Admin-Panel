<?php

namespace Database\Seeders;

use App\Models\Bonus;
use Illuminate\Database\Seeder;

class BonusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bonus::factory()->count(200)->create();
    }
}
