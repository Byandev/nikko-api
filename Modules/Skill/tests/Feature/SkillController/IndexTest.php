<?php

namespace Modules\Skill\Tests\Feature\SkillController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Modules\Skill\Models\Skill;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_skills(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Skill::factory()->count($count = fake()->numberBetween(2, 5))->create();

        $this->getJson(route('api.skills.index'))
            ->assertSuccessful()
            ->assertJsonCount($count, 'data');
    }
}
