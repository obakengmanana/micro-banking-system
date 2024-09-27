@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Accounts</h1>

    <div class="row">
        @foreach ($accounts as $account)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ ucfirst($account->account_type) }} Account</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Account Number: {{ $account->account_number }}</h6>

                        @php
                            // Retrieve all transactions for the account ordered by date
                            $transactions = $account->transactions()->orderBy('date')->get()->groupBy(function ($transaction) {
                                return $transaction->date->format('Y-m-d');
                            });

                            $openingBalance = $account->balance; // Initial balance before applying transactions

                            // Iterate through transactions grouped by date to calculate the latest closing balance
                            foreach ($transactions as $date => $trans) {
                                $totalDebits = $trans->where('type', 'debit')->sum('amount'); // Sum of debit amounts
                                $totalCredits = $trans->where('type', 'credit')->sum('amount'); // Sum of credit amounts

                                // Prevent debits from exceeding the opening balance
                                if ($totalDebits > $openingBalance) {
                                    $totalDebits = $openingBalance;
                                }

                                // Calculate the closing balance for the day
                                $closingBalance = $openingBalance - $totalDebits + $totalCredits;

                                // Update the opening balance for the next iteration
                                $openingBalance = $closingBalance;
                            }
                        @endphp

                        <p class="card-text">
                            <strong>Balance:</strong> R {{ number_format($openingBalance, 2) }}
                        </p>

                        <a href="{{ route('accounts.show', $account->id) }}" class="btn btn-primary">View Transactions</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
