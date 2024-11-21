<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubcategoriesTableSeeder extends Seeder
{
    /**
     * Seed the subcategories table.
     */
    public function run(): void
    {
        DB::table('subcategories')->insert([
            ['name' => 'GENERAL WORKS', 'slug' => Str::slug('GENERAL WORKS'), 'letter' => 'A'],
            ['name' => 'PHILOSOPHY. PSYCHOLOGY. RELIGION', 'slug' => Str::slug('PHILOSOPHY. PSYCHOLOGY. RELIGION'), 'letter' => 'B'],
            ['name' => 'AUXILIARY SCIENCES OF HISTORY', 'slug' => Str::slug('AUXILIARY SCIENCES OF HISTORY'), 'letter' => 'C'],
            ['name' => 'HISTORY: GENERAL AND OLD WORLD', 'slug' => Str::slug('HISTORY: GENERAL AND OLD WORLD'), 'letter' => 'D'],
            ['name' => 'HISTORY: GENERAL HISTORY OF AMERICA', 'slug' => Str::slug('HISTORY: GENERAL HISTORY OF AMERICA'), 'letter' => 'E'],
            ['name' => 'HISTORY: LOCAL HISTORY OF AMERICA', 'slug' => Str::slug('HISTORY: LOCAL HISTORY OF AMERICA'), 'letter' => 'F'],
            ['name' => 'GEOGRAPHY. ANTHROPOLOGY. RECREATION', 'slug' => Str::slug('GEOGRAPHY. ANTHROPOLOGY. RECREATION'), 'letter' => 'G'],
            ['name' => 'SOCIAL SCIENCES', 'slug' => Str::slug('SOCIAL SCIENCES'), 'letter' => 'H'],
            ['name' => 'POLITICAL SCIENCE', 'slug' => Str::slug('POLITICAL SCIENCE'), 'letter' => 'J'],
            ['name' => 'LAW', 'slug' => Str::slug('LAW'), 'letter' => 'K'],
            ['name' => 'EDUCATION', 'slug' => Str::slug('EDUCATION'), 'letter' => 'L'],
            ['name' => 'MUSIC AND BOOKS ON MUSIC', 'slug' => Str::slug('MUSIC AND BOOKS ON MUSIC'), 'letter' => 'M'],
            ['name' => 'FINE ARTS', 'slug' => Str::slug('FINE ARTS'), 'letter' => 'N'],
            ['name' => 'LANGUAGE AND LITERATURE', 'slug' => Str::slug('LANGUAGE AND LITERATURE'), 'letter' => 'P'],
            ['name' => 'SCIENCE', 'slug' => Str::slug('SCIENCE'), 'letter' => 'Q'],
            ['name' => 'MEDICINE', 'slug' => Str::slug('MEDICINE'), 'letter' => 'R'],
            ['name' => 'AGRICULTURE', 'slug' => Str::slug('AGRICULTURE'), 'letter' => 'S'],
            ['name' => 'TECHNOLOGY', 'slug' => Str::slug('TECHNOLOGY'), 'letter' => 'T'],
            ['name' => 'MILITARY SCIENCE', 'slug' => Str::slug('MILITARY SCIENCE'), 'letter' => 'U'],
            ['name' => 'NAVAL SCIENCE', 'slug' => Str::slug('NAVAL SCIENCE'), 'letter' => 'V'],
            ['name' => 'LIBRARY SCIENCE', 'slug' => Str::slug('LIBRARY SCIENCE'), 'letter' => 'Z'],
        ]);
    }
}
