<?php

namespace Database\Seeders;
use App\Models\Country;
use App\Models\User;
use App\Models\UserProfile;
use Database\Factories\User\SignUpUserFactory;
use Illuminate\Database\Seeder;

class SignUpUserSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factory = new SignUpUserFactory();
        $factory->count(200)
                ->has( UserProfile::factory()->count(1) )
                ->create();
    }
}
