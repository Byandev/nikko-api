<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Enums\AccountType;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory()->create()->id,
            'type' => collect(AccountType::cases())->random()->value,
            'title' => fake()->jobTitle(),
            'bio' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the account is freelancer
     */
    public function freelancer(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => AccountType::FREELANCER->value,
            ];
        });
    }

    /**
     * Indicate that the account is client
     */
    public function client(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => AccountType::CLIENT->value,
            ];
        });
    }
}
