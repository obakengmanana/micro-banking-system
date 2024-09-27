<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;

class AccountTransactionController extends Controller
{
    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request to ensure the amount is positive
        $request->validate([
            'account_id' => 'required|exists:accounts,id', // Ensures account exists
            'amount' => 'required|numeric|min:1', // Ensures the amount is a positive number
            'type' => 'required|in:debit,credit', // Ensures the type is either debit or credit
            'date' => 'required|date', // Ensures the date is a valid date
        ]);

        // Create the transaction
        $transaction = new AccountTransaction([
            'account_id' => $request->account_id,
            'amount' => abs($request->amount), // Ensure the amount is always positive
            'type' => $request->type,
            'date' => $request->date,
        ]);

        // Adjust the account balance
        $account = Account::find($request->account_id);
        if ($request->type === 'debit') {
            $account->balance -= $transaction->amount;
        } else {
            $account->balance += $transaction->amount;
        }
        $account->save();

        // Save the transaction
        $transaction->save();

        // Return a response or redirect as needed
        return redirect()->back()->with('success', 'Transaction created successfully.');
    }
}
