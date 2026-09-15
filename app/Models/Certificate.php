<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    public const TYPE_PREMIUM = 'premium';

    public const TYPE_STARTER = 'starter';

    protected $fillable = [
        'user_id', 'type', 'code', 'qr_payload', 'pdf_path', 'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isStarter(): bool
    {
        return $this->type === self::TYPE_STARTER;
    }

    public function isPremium(): bool
    {
        return $this->type === self::TYPE_PREMIUM;
    }

    public function label(): string
    {
        return $this->isStarter()
            ? 'Certificat de participation Starter'
            : 'Certification Premium FoodLab Academy';
    }
}
