<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use Notifiable;


    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role == 'ADMIN';
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'birthdate',
        'gender',
        'program',
        'year_level',
    ];


    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->role = 'USER';
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function interests()
    {
        return $this->belongsToMany(Subcategory::class, 'user_interests', 'user_id', 'subcategory_id');
    }

    public function programs()
    {
        return $this->belongsTo(Program::class, 'program_id'); 
    }
    
    public function year_levels()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id');
    }

    public function readingProgress()
    {
        return $this->hasMany(ReadingProgress::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorites::class);
    }

    public function readingList()
    {
        return $this->hasMany(ReadingList::class);
    }
}
