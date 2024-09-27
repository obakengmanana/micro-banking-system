<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Use updateOrCreate to avoid duplicate entries
        User::updateOrCreate(
            ['email' => env('DEFAULT_USER_EMAIL', 'user@example.com')],
            [
                'name' => env('DEFAULT_USER_NAME', 'Default User'),
                'password' => Hash::make(env('DEFAULT_USER_PASSWORD', 'password')),
            ]
        );

        // Create additional random users with unique emails
        User::factory()->count(4)->create();
    }
}
