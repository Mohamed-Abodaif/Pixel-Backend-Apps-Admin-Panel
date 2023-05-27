<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkSector\SystemConfigurationModels\CustodySender;

class CustodySenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustodySender::factory()->count(200)->create();
    }
}
