<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReadingList extends Model
{
    use HasFactory, SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'e_book_id',
       
    ];


     /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the eBook that this review is for.
     */
    public function eBook()
    {
        return $this->belongsTo(eBooks::class);
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class); // assuming many-to-many relationship
    }
}
