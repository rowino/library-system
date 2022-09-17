<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'author'];

    protected $appends = ['liked'];

    protected $with = ['likers'];

    public function libraries()
    {
        return $this->belongsToMany(Library::class, 'library_books')
                    ->withTimestamps()
                    ->withPivot('quantity');
    }

    public function likers()
    {
        return $this->belongsToMany(User::class, 'user_likes')
                    ->withTimestamps();
    }

    public function getLikedAttribute()
    {
        return $this->likers->pluck('id')->contains(auth()->id());
    }
}
