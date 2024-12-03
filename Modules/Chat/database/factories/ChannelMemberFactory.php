<?php

namespace Modules\Chat\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\User;
use Modules\Chat\Models\Channel;

class ChannelMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Chat\Models\ChannelMember::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'channel_id' => fn () => Channel::factory()->create()->id,
            'model_type' => User::class,
            'model_id' => fn () => User::factory()->create()->id,
            'last_read_at' => now(),
        ];
    }
}
