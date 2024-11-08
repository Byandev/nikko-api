<?php

namespace Modules\Project\Tests\Feature\ProjectController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Project\Models\Project;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_show_project(): void
    {
        $project = Project::factory()->create();

        $this->getJson(route('api.project.show', ['project' => $project]))
            ->assertSuccessful();
    }
}
