<?php

namespace Modules\Media\Tests\Feature\MediaController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function testUploadMedia(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.medias.store'), [
            'file' => UploadedFile::fake()->image('test.png'),
        ])
            ->assertSuccessful();
    }
}
