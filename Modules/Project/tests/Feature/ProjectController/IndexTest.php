<?php

namespace Modules\Project\Tests\Feature\ProjectController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Models\Project;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_list_projects(): void
    {
        Project::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create(['status' => ProjectStatus::ACTIVE->value]);

        $this->getJson(route('api.projects.index', [
            'include' => 'account.user.avatar',
        ]))
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $count]);
    }
}
