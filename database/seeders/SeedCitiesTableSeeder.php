<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SeedCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $json = File::get('database/seeders/Data/areas.json');

        $cities = json_decode($json, true);
        DB::table('cities')->insert($cities);
    }
}
