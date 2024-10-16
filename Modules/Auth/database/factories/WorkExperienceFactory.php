<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Enums\EmploymentType;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\WorkExperience;

class WorkExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = WorkExperience::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_id' => fn () => Account::factory()->create()->id,
            'job_title' => fake()->jobTitle,
            'company' => fake()->company,
            'website' => fake()->url,
            'country' => fake()->country,
            'employment' => fake()->randomElement([
                EmploymentType::FULL_TIME->value,
                EmploymentType::PART_TIME->value,
                EmploymentType::INTERN->value,
                EmploymentType::CONTRACT->value,
            ]),
            'description' => fake()->text,
            'start_month' => fake()->numberBetween(1, 12),
            'start_year' => fake()->numberBetween(2016, 2019),
            'end_month' => fake()->numberBetween(1, 12),
            'end_year' => fake()->numberBetween(2020, 2024),
            'is_current' => false,
        ];
    }
}
