<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Assumes you have a User factory
            'account_type' => $this->faker->randomElement(['Savings', 'Check', 'Credit']),
            'account_number' => strtoupper($this->faker->unique()->bothify('ACC###??')),
            'balance' => $this->faker->numberBetween(1000, 10000), // Random starting balance
        ];
    }
}
