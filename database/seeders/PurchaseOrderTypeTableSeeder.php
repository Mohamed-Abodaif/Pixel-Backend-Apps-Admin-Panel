<?php

namespace Database\Seeders;

use App\Models\PurchaseOrderType;
use Illuminate\Database\Seeder;

class PurchaseOrderTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PurchaseOrderType::factory()->count(200)->create();
    }
}
