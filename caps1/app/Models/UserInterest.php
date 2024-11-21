<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    use HasFactory;


    // Define the fillable properties
    protected $fillable = ['user_id', 'subcategory_id'];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Category model (or Interest model)
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class); // or Interest::class
    }
}
