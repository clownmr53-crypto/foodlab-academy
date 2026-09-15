<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'module_id', 'title', 'slug', 'content', 'order',
        'video_provider', 'video_id', 'duration', 'is_premium_only', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_premium_only' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }
}
