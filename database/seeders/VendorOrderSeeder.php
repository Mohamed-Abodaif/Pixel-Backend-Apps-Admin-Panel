<?php

namespace Database\Seeders;

use App\Models\VendorOrder;
use Illuminate\Database\Seeder;

class VendorOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VendorOrder::factory()->count(200)->create();
    }
}
