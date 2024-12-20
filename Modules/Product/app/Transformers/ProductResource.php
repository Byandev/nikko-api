<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Media\Transformers\MediaResource;
use Modules\Product\Models\Product;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parentId' => $this->parent_id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'isActive' => $this->is_active,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,

            'variants' => ProductResource::collection($this->whenLoaded('variants')),
            'options' => ProductOptionResource::collection($this->whenLoaded('options')),
            'attachments' => MediaResource::collection($this->whenLoaded('attachments')),
            'attributes' => ProductAttributeResource::collection($this->whenLoaded('attributes')),
        ];
    }
}
