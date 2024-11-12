<?php

namespace Modules\Project\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Account;
use Modules\Project\Enums\ProjectLength;
use Modules\Project\Enums\ProposalStatus;
use Modules\Project\Models\Project;

class ProposalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Project\Models\Proposal::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_id' => fn () => Account::factory()->freelancer()->create()->id,
            'project_id' => fn () => Project::factory()->create()->id,
            'cover_letter' => fake()->paragraph,
            'bid' => $bid = fake()->numberBetween(100, 5000),
            'transaction_fee' => 0.5 * $bid,
            'length' => fake()->randomElement([
                ProjectLength::SHORT_TERM->value,
                ProjectLength::MEDIUM_TERM->value,
                ProjectLength::LONG_TERM->value,
                ProjectLength::EXTENDED->value,
            ]),
            'status' => ProposalStatus::SUBMITTED->value,
        ];
    }
}
