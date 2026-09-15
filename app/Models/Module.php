<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'deliverable', 'order', 'is_premium_only', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_premium_only' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
}
