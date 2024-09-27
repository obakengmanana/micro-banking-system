<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Handle post-registration tasks such as seeding default accounts and transactions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function registered(Request $request, $user)
    {
        // Check if the user has any accounts
        if ($user->accounts()->count() === 0) {
            // Seed default accounts and transactions
            $this->seedDefaultAccountsAndTransactions($user);
        }
    }

    /**
     * Seed default accounts and transactions for a new user.
     *
     * @param \App\Models\User $user
     * @return void
     */
    private function seedDefaultAccountsAndTransactions(User $user)
    {
        // Predefined account types
        $accountTypes = ['Savings', 'Check', 'Credit'];

        // Ensure the maximum number of accounts does not exceed the available account types
        $maxAccounts = min(count($accountTypes), rand(2, 5));

        // Create accounts for the user with random types
        $accounts = collect($accountTypes)
            ->shuffle() // Shuffle to randomize types
            ->take($maxAccounts) // Take only up to the maximum allowed
            ->map(function ($type) use ($user) {
                return [
                    'user_id' => $user->id,
                    'account_type' => $type,
                    'account_number' => strtoupper(uniqid()),
                    'balance' => rand(500, 5000),
                ];
            });

        // Insert accounts into the database
        $user->accounts()->createMany($accounts->toArray());

        // Seed transactions for each account
        foreach ($user->accounts as $account) {
            $transactionCount = rand(10, 20); // Random number of transactions
            for ($i = 0; $i < $transactionCount; $i++) {
                $account->transactions()->create([
                    'date' => now()->subDays(rand(1, 180)),
                    'amount' => abs(rand(50, 500)), // Positive amounts
                    'type' => rand(0, 100) < 75 ? 'debit' : 'credit', // 75% chance of debit
                ]);
            }
        }
    }
}
