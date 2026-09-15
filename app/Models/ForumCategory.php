<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function threads(): HasMany
    {
        return $this->hasMany(ForumThread::class);
    }
}
