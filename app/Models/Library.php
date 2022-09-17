<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    public function books()
    {
        return $this->belongsToMany(Book::class, 'library_books')
                    ->withTimestamps()
                    ->withPivot('quantity');
    }
}
