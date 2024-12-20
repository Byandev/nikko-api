<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductOption;
use Modules\Product\Transformers\ProductResource;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Product::class)
            ->paginate($request->input('per_page', 10));

        return ProductResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'is_active' => 'required|boolean',
            'attachments' => 'required|array',
            'attachments.*' => 'required|numeric|exists:media,id',
            'categories' => 'required|array',
            'categories.*' => 'required|numeric|exists:categories,id',
            'options' => 'array',
            'options.*.name' => 'required|string|max:255',
            'options.*.choices' => 'required|array',
            'options.*.choices.*' => 'required|string|max:255',
            'variants' => 'array',
            'variants.*.title' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric',
            'variants.*.attributes' => 'required|array',
            'variants.*.attributes.*.option' => 'required|string|max:255',
            'variants.*.attributes.*.value' => 'required|string|max:255',
        ]);

        $product = Product::create($data);

        $product->categories()->sync($request->post('categories', []));

        if (count($request->input('options', [])) > 0) {
            $options = $request->input('options', []);

            foreach ($options as $option) {
                $productOption = ProductOption::create(['product_id' => $product->id, 'name' => $option['name']]);

                $productOption->choices()
                    ->createMany(
                        collect($option['choices'])->map(fn ($name) => ['name' => $name])->toArray()
                    );
            }
        }

        if (count($request->input('variants', [])) > 0) {
            $variants = $request->input('variants', []);

            foreach ($variants as $variant) {
                $productVariant = Product::create([
                    'parent_id' => $product->id,
                    'title' => $variant['title'],
                    'price' => $variant['price'],
                    'description' => $product->description,
                    'is_active' => $product->is_active,
                ]);

                $attributes = collect($variant['attributes'])
                    ->map(function ($item) use ($product) {
                        $option = $product->options()
                            ->with('choices')
                            ->where('name', $item['option'])
                            ->first();

                        return [
                            'product_option_id' => $option->id,
                            'product_option_choice_id' => $option->choices->where('name', $item['value'])->first()->id,
                        ];
                    })
                    ->toArray();

                $productVariant->attributes()->createMany($attributes);
            }
        }

        Media::whereIn('id', $request->post('attachments'))
            ->get()
            ->each(function (Media $media) use ($product) {
                $media->move($product, MediaCollectionType::PRODUCT_ATTACHMENTS->value);
            });

        $product->loadMissing(['attachments', 'options.choices', 'variants.attributes.choice', 'variants.attributes.option']);

        return ProductResource::make($product);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('product::show');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
