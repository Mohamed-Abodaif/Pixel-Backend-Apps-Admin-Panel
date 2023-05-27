<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PersonalSector\PersonalTransactions\Inflow\Custody;

class CustodyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Custody::factory()->count(200)->create();
    }
}
