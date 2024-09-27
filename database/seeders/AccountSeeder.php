<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            $accountsCount = rand(2, 5); // Randomly create between 2 and 5 accounts per user

            for ($i = 0; $i < $accountsCount; $i++) {
                // Random account type
                $accountType = ['Savings', 'Check', 'Credit'][array_rand(['Savings', 'Check', 'Credit'])];

                // Random balance between 1000 and 10000
                $startingBalance = rand(1000, 10000);

                // Create the account
                $account = Account::create([
                    'user_id' => $user->id,
                    'account_type' => $accountType,
                    'account_number' => strtoupper(uniqid()),
                    'balance' => $startingBalance, // Set a positive starting balance
                ]);

                // Log the starting balance and account ID of the created account
                Log::info("Created account ID {$account->id} for user {$user->id} - Account Type: {$accountType}, Starting Balance: {$startingBalance}");
            }
        }
    }
}
