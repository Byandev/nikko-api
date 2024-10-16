<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\Education;

class EducationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Education::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_id' => fn () => Account::factory()->create()->id,
            'degree' => fake()->word,
            'country' => fake()->country,
            'description' => fake()->text,
            'start_month' => fake()->numberBetween(1, 12),
            'start_year' => fake()->numberBetween(2016, 2019),
            'end_month' => fake()->numberBetween(1, 12),
            'end_year' => fake()->numberBetween(2020, 2024),
        ];
    }
}
