<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Models\Category;
use Modules\Category\Transformers\CategoryResource;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Category::class)
            ->paginate($request->input('per_page', 10));

        return CategoryResource::collection($data);
    }

    /**
     * Show the specified resource.
     */
    public function show(Category $category)
    {
        return CategoryResource::make($category);
    }
}
