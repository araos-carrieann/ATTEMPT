<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            PasswordResetTokensTableSeeder::class,
            SessionsTableSeeder::class,
            CategoriesTableSeeder::class,
            SubcategoriesTableSeeder::class, 
            YearLevelTableSeeder::class,     
            ProgramTableSeeder::class,    
        ]);
    }
}
