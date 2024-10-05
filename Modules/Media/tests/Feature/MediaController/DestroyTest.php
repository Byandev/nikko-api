<?php

namespace Feature\MediaController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Modules\Media\Models\Media;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_media(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $media = Media::factory()->create();

        $this->deleteJson(route('api.medias.destroy', ['media' => $media]))
            ->assertSuccessful();

        $this->assertDatabaseMissing('media', ['uuid' => $media->uuid]);
    }
}
