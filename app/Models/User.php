<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'google_id', 'password', 'country', 'sector', 'level',
    'role', 'plan', 'plan_activated_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'plan_activated_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasPlan(?string $plan = null): bool
    {
        if ($plan === null) {
            return in_array($this->plan, ['starter', 'premium'], true);
        }

        if ($plan === 'starter') {
            return in_array($this->plan, ['starter', 'premium'], true);
        }

        return $this->plan === $plan;
    }

    public function isPremium(): bool
    {
        return $this->plan === 'premium';
    }

    public function canAccessModule(Module $module): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (! $module->is_premium_only) {
            return $this->hasPlan('starter');
        }

        return $this->isPremium();
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function calculatorSessions(): HasMany
    {
        return $this->hasMany(CalculatorSession::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }
}
