<?php

namespace Modules\Tool\Tests\Feature\ToolController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Modules\Tool\Models\Tool;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_tools(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Tool::factory()->count($count = fake()->numberBetween(2, 5))->create();

        $this->getJson(route('api.tools.index'))
            ->assertSuccessful()
            ->assertJsonCount($count, 'data');
    }
}
