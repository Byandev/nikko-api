<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\PasswordReset;
use Modules\Auth\Models\User;

class PasswordResetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = PasswordReset::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory()->create()->id,
            'token' => fake()->bothify('######'),
            'expires_at' => now()->addMinutes(15),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subMinute(),
        ]);
    }
}
