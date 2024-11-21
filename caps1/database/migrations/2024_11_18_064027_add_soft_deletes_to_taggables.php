<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('taggables', function (Blueprint $table) {
            $table->timestamps();  // Adds the deleted_at column to the taggables pivot table
        });
    }

    public function down(): void
    {
        Schema::table('taggables', function (Blueprint $table) {
            $table->timestamps();  // Removes the deleted_at column
        });
    }
};
