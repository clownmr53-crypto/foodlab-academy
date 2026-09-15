<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'provider', 'plan', 'amount', 'currency', 'status',
        'external_id', 'payload', 'receipt_number', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
