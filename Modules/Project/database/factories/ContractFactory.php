<?php

namespace Modules\Project\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Account;
use Modules\Project\Enums\ContractStatus;
use Modules\Project\Models\Contract;
use Modules\Project\Models\Project;
use Modules\Project\Models\Proposal;

class ContractFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Contract::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_id' => $account_id = fn () => Account::factory()->create()->id,
            'project_id' => $project_id = fn () => Project::factory()->create()->id,
            'proposal_id' => fn () => Proposal::factory()->create(['account_id' => $account_id, 'project_id' => $project_id])->id,
            'amount' => $amount = fake()->numberBetween(100, 1000),
            'platform_fee_percentage' => 0.05,
            'total_amount' => $amount * 1.05,
            'status' => ContractStatus::PENDING->value,
        ];
    }
}
