<?php

namespace database\seeders;

use Illuminate\Database\Seeder;

class CountryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SeedCountriesTableSeeder::class,
            SeedCitiesTableSeeder::class,
            SeedAreasTableSeeder::class
        ]);
    }
}
