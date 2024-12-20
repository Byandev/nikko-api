<?php

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductOption;

class ProductAttributeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Product\Models\ProductAttribute::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_id' => $product_id = fn () => Product::factory()->create()->id,
            'product_option_id' => $product_option_id = fn () => ProductOption::factory()->create(['product_id' => $product_id])->id,
            'product_option_choice_id' => fn () => ProductOption::factory()->create(['product_id' => $product_id, 'product_option_id' => $product_option_id])->id,
        ];
    }
}
