<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AccountController extends Controller
{
    protected $accountService;

    // Inject the AccountService via constructor
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index()
    {
        $accounts = Auth::user()->accounts;
        return view('accounts.index', compact('accounts'));
    }

    public function show(Request $request, $accountId)
    {
        // Fetch the account ensuring it belongs to the authenticated user
        $account = Account::where('id', $accountId)->where('user_id', Auth::id())->firstOrFail();

        // Get date range from request or set defaults
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subMonths(6);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();

        // Use the service to get daily summaries
        $dailySummaries = $this->accountService->getDailySummaries($account, $startDate, $endDate);

        return view('accounts.daily_report', compact('dailySummaries', 'account'));
    }
}

