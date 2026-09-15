<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceTemplate extends Model
{
    protected $fillable = [
        'title', 'description', 'file_path', 'external_url', 'is_published', 'order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function hasDownload(): bool
    {
        return filled($this->file_path) || filled($this->external_url);
    }
}
