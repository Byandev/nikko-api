<?php

namespace Modules\Skill\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Skill\Models\Skill;
use Modules\Skill\Transformers\SkillResource;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::all();

        return SkillResource::collection($skills);
    }
}
