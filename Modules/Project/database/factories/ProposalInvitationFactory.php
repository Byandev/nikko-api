<?php

namespace Modules\Project\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Account;
use Modules\Project\Models\Project;

class ProposalInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Project\Models\ProposalInvitation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_id' => fn () => Account::factory()->freelancer()->create()->id,
            'project_id' => fn () => Project::factory()->create()->id,
            'message' => fake()->paragraph,
        ];
    }
}
