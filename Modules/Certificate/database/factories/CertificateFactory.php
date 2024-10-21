<?php

namespace Modules\Certificate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Account;

class CertificateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Certificate\Models\Certificate::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_id' => fn () => Account::factory()->create()->id,
            'title' => fake()->word,
            'issued_date' => fake()->date('Y-m-d'),
            'reference_id' => fake()->numerify('#######'),
            'url' => fake()->url,
        ];
    }
}
