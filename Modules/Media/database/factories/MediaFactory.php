<?php

namespace Modules\Media\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\User;
use Modules\Media\Enums\MediaCollectionType;

class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Media\Models\Media::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'collection_name' => MediaCollectionType::UNASSIGNED,
            'name' => 'test',
            'file_name' => 'test.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'size' => 1,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
            'model_type' => User::class,
            'model_id' => fn () => User::factory()->create()->id,
        ];
    }
}
