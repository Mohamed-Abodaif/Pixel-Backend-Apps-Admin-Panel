<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),


            'category' => $this->faker->randomElement(['assets',
            'company_operation',
            'client_po',
            'marketing',
            'client_visits_preorders',
            'purchase_for_inventory',
            'taxes',
            'insurances',
            'exchange_currency',]),

            'status'=>$this->faker->randomElement([1,0]),

            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
        ];
    }
}
