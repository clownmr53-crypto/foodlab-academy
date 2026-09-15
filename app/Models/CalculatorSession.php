<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalculatorSession extends Model
{
    protected $fillable = ['user_id', 'title', 'inputs', 'results'];

    protected function casts(): array
    {
        return [
            'inputs' => 'array',
            'results' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
