<?php

namespace Modules\Product\Tests\Feature\Admin\ProductController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Modules\Product\Models\Product;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanListProducts()
    {
        Sanctum::actingAs(User::factory()->create());

        Product::factory()
            ->count($count = fake()->numberBetween(1, 10))->create();

        $this->getJson(route('api.admin.products.index'))
            ->assertSuccessful();
    }
}
