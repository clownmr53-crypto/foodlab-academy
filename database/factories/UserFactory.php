<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'country' => 'Bénin',
            'sector' => 'Restauration',
            'level' => 'débutant',
            'role' => 'student',
            'plan' => null,
            'plan_activated_at' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin', 'plan' => 'premium', 'plan_activated_at' => now()]);
    }

    public function starter(): static
    {
        return $this->state(fn () => ['plan' => 'starter', 'plan_activated_at' => now()]);
    }

    public function premium(): static
    {
        return $this->state(fn () => ['plan' => 'premium', 'plan_activated_at' => now()]);
    }
}
