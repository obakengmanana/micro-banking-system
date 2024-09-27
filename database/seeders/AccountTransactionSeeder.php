<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccountTransactionSeeder extends Seeder
{
    public function run()
    {
        // Fetch all accounts
        $accounts = Account::all();

        foreach ($accounts as $account) {
            $updatedBalance = $account->balance;

            // Generate random transactions for the past 6 months
            $startDate = now();
            $endDate = now()->subMonths(6);
            $validTransactionsCount = 0;

            while ($startDate->greaterThan($endDate)) {
                // Randomly decide whether to generate transactions
                if (rand(0, 100) < 50) { // 50% chance to generate transactions
                    $startDate->subDay();
                    continue;
                }

                // Generate random transactions for the day (1 to 3 transactions per day)
                $transactionsCount = rand(1, 3);

                for ($i = 0; $i < $transactionsCount; $i++) {
                    // Determine transaction type (75% chance for debit)
                    $type = rand(0, 100) < 75 ? 'debit' : 'credit';

                    // Set the amount within the cap
                    $amount = rand(30, 50); // Amount capped between 30 and 50
                    
                    // Adjust amount for credit transactions if needed
                    if ($type === 'credit') {
                        // Credit is capped to 80% of the current balance, ensuring it stays within the limit
                        $amount = min($amount, round($updatedBalance * 0.80));
                    }

                    // Prepare transaction data
                    $data = [
                        'account_id' => $account->id,
                        'date' => $startDate,
                        'amount' => $amount,
                        'type' => $type,
                    ];

                    // Validate the transaction data
                    $validator = Validator::make($data, [
                        'account_id' => 'required|exists:accounts,id',
                        'date' => 'required|date',
                        'amount' => 'required|numeric|min:1',
                        'type' => 'required|in:debit,credit',
                    ]);

                    if ($validator->fails()) {
                        Log::error("Validation failed for transaction: " . json_encode($validator->errors()));
                        continue;
                    }

                    // Adjust balance based on transaction type
                    if ($type === 'debit') {
                        // Skip debit if it would cause a negative balance
                        if ($updatedBalance - $amount < 0) {
                            Log::info("Skipping debit of {$amount} on account {$account->id} as it would cause a negative balance.");
                            continue;
                        }
                        $updatedBalance -= $amount;
                    } else {
                        $updatedBalance += $amount;
                    }

                    // Create the transaction
                    AccountTransaction::create($data);
                    $validTransactionsCount++;

                    Log::info("Account {$account->id} - Transaction: $type of {$amount} on {$startDate}");
                }

                // Move to the previous day
                $startDate->subDay();
            }
        }
    }
}
