<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordResetTokensTableSeeder extends Seeder
{
    /**
     * Seed the password_reset_tokens table.
     */
    public function run(): void
    {
        DB::table('password_reset_tokens')->insert([
            [
                'email' => 'john.doe@example.com',
                'token' => Str::random(60),
                'created_at' => now(),
            ],
            [
                'email' => 'alice.admin@example.com',
                'token' => Str::random(60),
                'created_at' => now(),
            ],
            // Add more tokens as needed
        ]);
    }
}
