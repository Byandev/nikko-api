<?php

namespace Modules\Chat\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\User;
use Modules\Chat\Models\Channel;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Chat\Models\Message::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'channel_id' => fn () => Channel::factory()->create()->id,
            'sender_id' => fn () => User::factory()->create()->id,
            'content' => fake()->paragraph,
        ];
    }
}
