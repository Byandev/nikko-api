<?php

namespace Modules\Save\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\User;

class SaveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Save\Models\Save::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'savable_id' => fn () => User::factory()->create()->id,
            'savable_type' => User::class,
            'saver_id' => fn () => User::factory()->create()->id,
            'saver_type' => User::class,
        ];
    }
}
