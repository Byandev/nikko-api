<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Enums\LanguageProficiencyType;
use Modules\Auth\Models\Language;
use Modules\Auth\Models\User;

class LanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Language::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory()->create()->id,
            'name' => fake()->languageCode,
            'proficiency' => fake()->randomElement([
                LanguageProficiencyType::BEGINNER->value,
                LanguageProficiencyType::INTERMEDIATE->value,
                LanguageProficiencyType::FLUENT->value,
            ]),
        ];
    }
}
