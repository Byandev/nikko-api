<?php

namespace Modules\Portfolio\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Account;
use Modules\Portfolio\Models\Portfolio;

class PortfolioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Portfolio::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_id' => fn () => Account::factory()->create()->id,
            'title' => fake()->title,
            'description' => fake()->paragraph,
            'url' => fake()->url,
        ];
    }
}
