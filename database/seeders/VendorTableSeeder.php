<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkSector\VendorsModule\Vendor;

class VendorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vendor::factory()->count(200)->create();
    }
}
