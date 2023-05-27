<?php

namespace Database\Seeders;

use App\Models\SalesInvoice;
use Illuminate\Database\Seeder;

class SalesInvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalesInvoice::factory()->count(200)->create();
    }
}
