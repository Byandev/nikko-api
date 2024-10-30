<?php

namespace Modules\Project\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Account;
use Modules\Project\Enums\ExperienceLevel;
use Modules\Project\Enums\ProjectLength;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Project\Models\Project::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_id' => fn () => Account::factory()->create()->id,
            'title' => fake()->jobTitle,
            'description' => fake()->paragraph,
            'estimated_budget' => fake()->numberBetween(100, 100000),
            'length' => fake()->randomElement([
                ProjectLength::SHORT_TERM->value,
                ProjectLength::MEDIUM_TERM->value,
                ProjectLength::LONG_TERM->value,
                ProjectLength::EXTENDED->value,
            ]),
            'experience_level' => fake()->randomElement([
                ExperienceLevel::ANY->value,
                ExperienceLevel::ENTRY->value,
                ExperienceLevel::INTERMEDIATE->value,
                ExperienceLevel::EXPERT->value,
            ]),
        ];
    }
}
