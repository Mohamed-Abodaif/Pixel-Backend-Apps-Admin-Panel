<?php

namespace Database\Factories\User;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

abstract class UserFactory extends Factory
{

    protected $model = User::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $first_name  = $this->faker->name();
        $last_name = $this->faker->name();
        return [
            'first_name' => $first_name,
            'last_name' => $last_name,
            "name" => $first_name . " " , $last_name,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make(22442244),
            'mobile' => $this->faker->unique()->phoneNumber(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'verification_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
