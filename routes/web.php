<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\AccountController;

// Home route - redirect to accounts index
Route::get('/', function () {
    return redirect()->route('accounts.index'); // Redirect to accounts index
});

// Authentication routes - this sets up login, registration, etc.
Auth::routes();

// Group routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Account listing route
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');

    // Account daily movement report route
    Route::get('/accounts/{account}', [AccountController::class, 'show'])->name('accounts.show');
});

Route::get('/accounts/{account}/balance', [AccountController::class, 'getBalance'])->name('accounts.balance');


// Redirect /home to /
Route::get('/home', function () {
    return redirect('/'); // Always redirect to the root
})->name('home');
