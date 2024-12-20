<?php

namespace Modules\Product\Tests\Feature\Admin\ProductController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Modules\Media\Models\Media;
use Modules\Product\Models\Product;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanCreateProduct()
    {
        Sanctum::actingAs(User::factory()->create());

        $data = Product::factory()->make()->toArray();

        $data['attachments'] = Media::factory()
            ->count(fake()->numberBetween(2, 5))
            ->create()->map(fn (Media $media) => $media->id)
            ->toArray();

        $this->postJson(route('api.admin.products.store'), $data)
            ->assertSuccessful();

        $this->assertDatabaseHas('products', Arr::except($data, ['attachments']));
    }

    public function testAdminCanCreateProductWithVariants()
    {
        Sanctum::actingAs(User::factory()->create());

        $data = Product::factory()->make()->toArray();

        $data['attachments'] = Media::factory()
            ->count(fake()->numberBetween(2, 5))
            ->create()->map(fn (Media $media) => $media->id)
            ->toArray();

        $data['options'] = [
            [
                'name' => 'Color',
                'choices' => ['Red', 'Green', 'Black'],
            ],
            [
                'name' => 'Size',
                'choices' => ['Small', 'Medium', 'Large'],
            ],
        ];

        $data['variants'] = collect(['Red', 'Green', 'Black'])
            ->crossJoin(['Small', 'Medium', 'Large'])
            ->map(function ($item) use ($data) {
                $color = $item[0];
                $size = $item[1];

                return [
                    'title' => $data['title']."-Variant-$size-$color",
                    'price' => $data['price'],
                    'attributes' => [
                        [
                            'option' => 'Color',
                            'value' => $color,
                        ],
                        [
                            'option' => 'Size',
                            'value' => $size,
                        ],
                    ],
                ];
            })
            ->toArray();

        $this->postJson(route('api.admin.products.store'), $data)
            ->dump()
            ->assertSuccessful();
    }
}
