<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Seed the categories table.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'FILIPINIANA',
                'slug' => Str::slug('filipiniana'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FOREIGN',
                'slug' => Str::slug('foreign'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FICTION',
                'slug' => Str::slug('fiction'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REFERENCES',
                'slug' => Str::slug('references'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
