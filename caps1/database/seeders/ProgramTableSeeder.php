<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramTableSeeder extends Seeder
{
    /**
     * Seed the program table.
     */
    public function run(): void
    {
        DB::table('programs')->insert([
            ['name' => 'BEEd'],
            ['name' => 'BSIT'],
            ['name' => 'BSENT'],
            ['name' => 'DOMT'],
            ['name' => 'DICT'],
        ]);
    }
}
