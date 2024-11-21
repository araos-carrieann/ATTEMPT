<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionsTableSeeder extends Seeder
{
    /**
     * Seed the sessions table.
     */
    public function run(): void
    {
        DB::table('sessions')->insert([
            [
                'id' => Str::random(40),
                'user_id' => 1, // Assuming ID for 'jdoe'
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'payload' => Str::random(200),
                'last_activity' => now()->timestamp,
            ],
            [
                'id' => Str::random(40),
                'user_id' => 2, // Assuming ID for 'admin'
                'ip_address' => '127.0.0.2',
                'user_agent' => 'Mozilla/5.0',
                'payload' => Str::random(200),
                'last_activity' => now()->timestamp,
            ],
            // Add more sessions as needed
        ]);
    }
}
