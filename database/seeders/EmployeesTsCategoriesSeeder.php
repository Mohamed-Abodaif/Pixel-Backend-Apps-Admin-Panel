<?php

namespace Database\Seeders;

use App\Models\SystemConfig\TimeSheetCategory;
use Illuminate\Database\Seeder;

class EmployeesTsCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TimeSheetCategory::insert(
            [
                [
                'name'=> 'General',
                ],
                [
                'name'=> 'inside your area',
                ],
                [
                'name'=> 'outside your area',
                ],
                [
                'name'=> 'Weekends & Vacations',
                ]
            ]
        );
    }
}
