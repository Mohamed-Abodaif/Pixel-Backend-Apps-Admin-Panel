<?php

namespace Database\Seeders;
use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Asset::factory()->count(200)->create();
    }
}
