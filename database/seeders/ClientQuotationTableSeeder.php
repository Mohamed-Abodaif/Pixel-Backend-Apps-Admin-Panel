<?php

namespace Database\Seeders;

use App\Models\ClientQuotation;
use Illuminate\Database\Seeder;

class ClientQuotationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientQuotation::factory()->count(200)->create();
    }
}
