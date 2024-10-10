<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\User;

class ChangeRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Auth\Models\ChangeRequest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'changeable_id' => fn () => User::factory()->create()->id,
            'changeable_type' => User::class,
            'field_name' => 'email',
            'from' => fake()->email(),
            'to' => fake()->email(),
            'token' => Hash::make('000000'),
        ];
    }
}
