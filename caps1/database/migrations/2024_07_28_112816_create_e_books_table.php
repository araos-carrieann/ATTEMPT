<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('e_books', function (Blueprint $table) {
            $table->id();
            $table->string('book_cover');
            $table->string('title');
            $table->string('isbn')->unique();
            $table->string('slug')->unique();
            $table->string('publisher');
            $table->string('language');
            $table->string('author');
            $table->text('description')->nullable();
            $table->integer('publication_year');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('lcc_classification');
            $table->string('ebook_file_path');
            $table->foreignId('uploader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_books');
    }
};
