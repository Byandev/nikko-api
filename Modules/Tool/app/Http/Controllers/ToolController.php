<?php

namespace Modules\Tool\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Tool\Models\Tool;
use Modules\Tool\Transformers\ToolResource;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tools = Tool::all();

        return ToolResource::collection($tools);
    }
}
