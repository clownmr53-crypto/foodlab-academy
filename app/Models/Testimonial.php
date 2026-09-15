<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'author_name', 'author_role', 'country', 'content', 'rating', 'is_published', 'order',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }
}
