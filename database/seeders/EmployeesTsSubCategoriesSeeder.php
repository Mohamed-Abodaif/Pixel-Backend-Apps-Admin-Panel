<?php

namespace Database\Seeders;

use App\Models\SystemConfig\TimeSheetSubCategory;
use Illuminate\Database\Seeder;

class EmployeesTsSubCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TimeSheetSubCategory::insert(
            [
                [
                    'timesheet_category_id' => 1,
                    'name' => 'at office',
                    'options' => json_encode([
                        'date',
                        'time',
                        'description',
                    ]),
                ],
                [
                    'timesheet_category_id' => 1,
                    'name' => 'work from home',
                    'options' => json_encode([
                        'date',
                        'time',
                        'description'
                    ]),
                ],
                [
                    'timesheet_category_id' => 2,
                    'name' => 'site visit',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description'
                    ]),
                ],
                [
                    'timesheet_category_id' => 2,
                    'name' => 'sales visit',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 2,
                    'name' => 'business meeting',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 2,
                    'name' => 'on job',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 2,
                    'name' => 'on project',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 2,
                    'name' => 'overtime',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 2,
                    'name' => 'site visit',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                //out side

                [
                    'timesheet_category_id' => 3,
                    'name' => 'site visit',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 3,
                    'name' => 'sales visit',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 3,
                    'name' => 'business meeting',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 3,
                    'name' => 'on job',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 3,
                    'name' => 'on project',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 3,
                    'name' => 'overtime',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 3,
                    'name' => 'site visit',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],

                //weekend

                [
                    'timesheet_category_id' => 4,
                    'name' => 'weekend',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 4,
                    'name' => 'official vacation',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 4,
                    'name' => 'vacation with permission',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 4,
                    'name' => 'emergency vacation',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 4,
                    'name' => 'vacation without permission',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 4,
                    'name' => 'leave with permission',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
                [
                    'timesheet_category_id' => 4,
                    'name' => 'leave without permission',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],

                [
                    'timesheet_category_id' => 4,
                    'name' => 'emergeny-leave',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],

                [
                    'timesheet_category_id' => 4,
                    'name' => 'at-home-without-work',
                    'options' => json_encode([
                        'date',
                        'time',
                        'client',
                        'client order',
                        'vendor',
                        'vendor order',
                        'country',
                        'description']),
                ],
            ]
        );
    }
}
