<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\SoftDeletes;

class eBooks extends Model
{
    use HasFactory, SoftDeletes;
    use HasTags;


    protected $fillable = [
        'title',
        'slug',
        'author',
        'description',
        'publication_year',
        'category_id',
        'lcc_classification',
        'publisher',
        'language',
        'ebook_file_path',
        'uploader_id',
        'published',
        'book_cover',
        'isbn',
    ];

    protected static function booted()
    {
        static::creating(function ($ebook) {
            $ebook->uploader_id = Auth::id(); // Set the uploader_id to the currently authenticated user
        });

        // Soft delete related data
        static::deleting(function ($eBook) {
            // Check if the reading list exists and then delete
            if ($eBook->readingList()->exists()) {
                $eBook->readingList()->delete(); // Delete related reading list if it exists
            }
            // Soft delete related progresses if they exist
            if ($eBook->readingProgress()->exists()) {
                $eBook->readingProgress()->delete(); // Soft delete related progresses
            }

            // Soft delete related favorites if they exist
            if ($eBook->userFavorites()->exists()) {
                $eBook->userFavorites()->delete(); // Soft delete related favorites
            }
        });

        // Hard delete related data (force delete)
        static::deleted(function ($eBook) {
            // Check if the deletion was permanent (force delete)
            if ($eBook->trashed()) {

                // Force delete related progresses if they exist
                if ($eBook->readingProgress()->exists()) {
                    $eBook->readingProgress()->forceDelete();
                }

                // Check if the reading list exists and then delete
                if ($eBook->readingList()->exists()) {
                    $eBook->readingList()->forceDelete(); // Delete related reading list if it exists
                }

                // Force delete related favorites if they exist
                if ($eBook->userFavorites()->exists()) {
                    $eBook->userFavorites()->forceDelete();
                }
            }
        });
    }
    // In eBooks model
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withPivot('deleted_at');
    }    

    // In Tag model
    public function eBooks()
    {
        return $this->belongsToMany(eBooks::class, 'taggables_id')->using(Taggable::class);
    }

    protected $casts = [
        'lcc_classification' => 'array', // This ensures the JSON data is handled as an array
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'e_book_subcategory', 'e_book_id', 'subcategory_id');
    }

    public function readingList()
    {
        return $this->hasMany(ReadingList::class, 'e_book_id');
    }

    public function readingProgress()
    {
        return $this->hasMany(ReadingProgress::class, 'e_book_id');
    }

    public function userFavorites()
    {
        return $this->hasMany(Favorites::class, 'e_book_id');
    }

    public function fronttags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'e_book_id'); // Adjust the foreign key if necessary
    }
}
