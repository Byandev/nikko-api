<?php

namespace Modules\Media\Tests\Feature\MediaController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Modules\Media\Models\Media;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_media(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $media = Media::factory()->create();

        $this->getJson(route('api.medias.show', ['media' => $media]))
            ->assertSuccessful();
    }
}
