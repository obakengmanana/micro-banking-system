<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Generate a random positive amount between 50 and 5000
        $amount = abs($this->faker->numberBetween(50, 5000));

        // Randomly assign debit or credit with a 75% chance of debit
        $type = $this->faker->randomElement(['debit', 'credit']);

        return [
            'account_id' => Account::factory(),
            'date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'amount' => $amount, // Ensure the amount is always positive
            'type' => $type,
        ];
    }
}
