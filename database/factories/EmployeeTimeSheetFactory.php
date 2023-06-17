<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientOrder;
use App\Models\Country;
use App\Models\SystemConfig\TimeSheetSubCategory;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeTimeSheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id'=>Client::inRandomOrder()->first()->id,
            'client_po_id'=>ClientOrder::inRandomOrder()->first()->id,
            'timesheet_sc_id'=>TimeSheetSubCategory::inRandomOrder()->first()->id,
            'start_date' => $this->faker->dateTimeInInterval('-7 days', '+ 5 days')->format('Y:m:d'),
            'end_date' => $this->faker->dateTimeInInterval('-7 days', '+ 6 days')->format('Y:m:d'),
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
            'country_id'=>Country::inRandomOrder()->first()->id,
            'location'=>$this->faker->sentence(),
            'night_shift'=>$this->faker->randomElement([1,0]),
            'user_id'=>User::inRandomOrder()->first()->id,
            'description'=>$this->faker->sentence(),
            'status'=>$this->faker->randomElement(['Draft','Pending','Edit Requested','Pending After Edit','Approved','Rejected']),
            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
        ];
    }
}
