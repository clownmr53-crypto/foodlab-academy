<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoachingRequest extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'message', 'preferred_slot', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
