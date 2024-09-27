@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Daily Movement Summary</h1>

    <!-- Date Range Filter Form -->
    <form action="{{ route('accounts.show', $account->id) }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="start_date" class="form-label">Start Date:</label>
            <input type="date" name="start_date" id="start_date" class="form-control" 
                   value="{{ request('start_date', '') }}"> <!-- Set default value to an empty string -->
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label">End Date:</label>
            <input type="date" name="end_date" id="end_date" class="form-control" 
                   value="{{ request('end_date', '') }}"> <!-- Set default value to an empty string -->
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Display Daily Summaries -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>Date</th>
                    <th>Opening Balance</th>
                    <th>Total Debits</th>
                    <th>Total Credits</th>
                    <th>Closing Balance</th>
                </tr>
            </thead>
<tbody>
    @foreach ($dailySummaries as $summary)
            <tr>
                <td>{{ $summary['date'] }}</td>
                <td>{{ number_format($summary['opening_balance'], 2) }}</td>
                <td>{{ number_format($summary['total_debits'], 2) }}</td>
                <td>{{ number_format($summary['total_credits'], 2) }}</td>
                <td>{{ number_format($summary['closing_balance'], 2) }}</td>
            </tr>
    @endforeach
</tbody>

        </table>
    </div>
</div>
@endsection
