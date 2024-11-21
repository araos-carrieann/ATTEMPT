<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $casts = [
        'name' => 'array',  // Automatically decode JSON if the 'name' field is a JSON column
    ];

    // Accessor to properly handle the nested JSON structure
    public function getNameAttribute($value)
    {
        // Decode the 'name' field if it's a nested JSON string
        $decodedValue = json_decode($value, true);
        
        // Return the 'en' value if it's a valid JSON structure
        if (is_array($decodedValue)) {
            return $decodedValue['en'] ?? ''; // Return the 'en' translation or an empty string if not found
        }

        return $value;  // Return the original value if it's not a JSON string
    }

    public function eBooks()
    {
        return $this->morphedByMany(eBooks::class, 'taggable')->withPivot('deleted_at');
    }

    public function taggables()
    {
        return $this->morphedByMany(eBooks::class, 'taggable')->withTrashed();
    }
}
