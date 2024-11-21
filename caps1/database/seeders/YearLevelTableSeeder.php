<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YearLevelTableSeeder extends Seeder
{
    /**
     * Seed the year_level table.
     */
    public function run(): void
    {
        DB::table('year_levels')->insert([
            ['yearlevel' => 'I'],
            ['yearlevel' => 'II'],
            ['yearlevel' => 'III'],
            ['yearlevel' => 'IV'],
        ]);
    }
}
