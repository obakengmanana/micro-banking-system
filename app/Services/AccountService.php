<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountTransaction;
use Carbon\Carbon;

class AccountService
{
    /**
     * Get daily summaries of transactions within a date range.
     *
     * @param Account $account
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getDailySummaries(Account $account, Carbon $startDate, Carbon $endDate)
    {
        $transactions = $this->getTransactions($account->id, $startDate, $endDate);

        // Calculate the opening balance before the start date
        $openingBalance = $this->getOpeningBalance($account, $startDate);

        $dailySummaries = [];
        $currentDate = $startDate->copy();

        // Iterate through each day from start to end date
        while ($currentDate->lte($endDate)) {
            $trans = $transactions->get($currentDate->format('Y-m-d'), collect());

            // Calculate daily debits and credits
            $totalDebits = $trans->where('type', 'debit')->sum('amount');
            $totalCredits = $trans->where('type', 'credit')->sum('amount');

            // Ensure that debits don't reduce balance below zero
            if ($openingBalance - $totalDebits < 0) {
                $totalDebits = 0;
            }

            $closingBalance = $openingBalance - $totalDebits + $totalCredits;

            // Create daily summary
            $dailySummaries[] = [
                'date' => $currentDate->format('Y-m-d'),
                'opening_balance' => $openingBalance,
                'total_debits' => $totalDebits,
                'total_credits' => $totalCredits,
                'closing_balance' => $closingBalance,
            ];

            // Update opening balance for the next day
            $openingBalance = $closingBalance;

            $currentDate->addDay();
        }

        return $dailySummaries;
    }

    /**
     * Retrieve transactions within a date range, grouped by date.
     *
     * @param int $accountId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return \Illuminate\Support\Collection
     */
    public function getTransactions($accountId, Carbon $startDate, Carbon $endDate)
    {
        return AccountTransaction::where('account_id', $accountId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get()
            ->groupBy(function ($transaction) {
                return $transaction->date->format('Y-m-d');
            });
    }

    /**
     * Calculate the correct opening balance before the filtered date range.
     *
     * @param Account $account
     * @param Carbon|null $startDate
     * @return float
     */
    public function getOpeningBalance(Account $account, Carbon $startDate)
    {
        // Calculate the opening balance up to the start date
        $transactions = AccountTransaction::where('account_id', $account->id)
            ->whereDate('date', '<', $startDate)
            ->get();

        $debits = $transactions->where('type', 'debit')->sum('amount');
        $credits = $transactions->where('type', 'credit')->sum('amount');

        // Adjust the account balance by past transactions
        return $account->balance + $credits - $debits;
    }
}
