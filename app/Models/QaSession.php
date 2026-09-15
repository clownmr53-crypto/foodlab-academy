<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QaSession extends Model
{
    protected $fillable = [
        'title', 'description', 'session_at', 'visio_link', 'replay_url', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'session_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }
}
