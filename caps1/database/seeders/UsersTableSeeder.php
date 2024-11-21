<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the users table.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'status' => 'active',
                'role' => 'USER',
                'student_id' => 'S1234567',
                'username' => 'jdoe',
                'last_name' => 'Doe',
                'first_name' => 'John',
                'middle_name' => 'A',
                'email' => 'john.doe@example.com',
                'birthdate' => '2000-01-01',
                'gender' => 'Male',
                'program_id' => 1,
                'year_level_id' => 2,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => 'active',
                'role' => 'ADMIN',
                'student_id' => 'A0000001',
                'username' => 'admin',
                'last_name' => 'Admin',
                'first_name' => 'Alice',
                'middle_name' => 'B',
                'email' => 'alice.admin@example.com',
                'birthdate' => '1985-05-15',
                'gender' => 'Female',
                'program_id' => null, // or set an appropriate program_id if needed
                'year_level_id' => null, // or set an appropriate year_level_id if needed
                'email_verified_at' => now(),
                'password' => Hash::make('adminpassword'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more users as needed
        ]);
    }
}
