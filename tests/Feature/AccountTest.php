<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\AccountTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_summary_calculation()
    {
        // Set up the user and account data
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id, 'balance' => 10000]);

        // Seed some transactions
        AccountTransaction::factory()->create(['account_id' => $account->id, 'type' => 'debit', 'amount' => 1000]);
        AccountTransaction::factory()->create(['account_id' => $account->id, 'type' => 'credit', 'amount' => 500]);

        // Act: Make a request to the route that displays account summaries
        $response = $this->actingAs($user)->get(route('accounts.show', $account->id));

        // Assert: Check that the response contains the expected data
        $response->assertStatus(200);
        $response->assertSee('Daily Movement Summary');
        $response->assertSee('Total Debits');
        $response->assertSee('Total Credits');
    }
}
