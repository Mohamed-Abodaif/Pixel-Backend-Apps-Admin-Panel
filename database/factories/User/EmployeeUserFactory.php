<?php

namespace Database\Factories\User;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeUserFactory extends UserFactory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return array_merge(
            parent::definition(),
            [
                'status' => User::EMPLOYEE_USER_STATUS[$this->faker->numberBetween(0,1)],
                'accepted_at' => now(),
                'user_type' => 'employee'
            ]
        );
    }
}
