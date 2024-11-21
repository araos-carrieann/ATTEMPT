<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'letter'];

    public function ebooks()
    {
        return $this->belongsToMany(EBooks::class, 'e_book_subcategory', 'subcategory_id', 'e_book_id');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interests', 'subcategory_id', 'user_id');
    }
    
}
