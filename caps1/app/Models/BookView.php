<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'e_book_id',
        'category',
        'subcategory_letter',
        'year_level',
        'program',
        'timestamp',
    ];
}
