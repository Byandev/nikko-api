<?php

namespace Modules\Portfolio\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Media\Transformers\MediaResource;
use Modules\Portfolio\Models\Portfolio;

/**
 * @mixin Portfolio
 */
class PortfolioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'images' => MediaResource::collection($this->whenLoaded('images')),
        ];
    }
}
