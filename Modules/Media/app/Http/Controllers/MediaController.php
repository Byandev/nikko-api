<?php

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Media\Transformers\MediaResource;

class MediaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $media = $user->addMediaFromRequest('file')
            ->toMediaCollection(MediaCollectionType::UNASSIGNED->value);

        return MediaResource::make($media);
    }

    /**
     * Show the specified resource.
     */
    public function show(Media $media)
    {
        return MediaResource::make($media);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        $media->delete();

        return response()->json([]);
    }
}
