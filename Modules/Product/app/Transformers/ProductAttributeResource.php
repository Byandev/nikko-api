<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Models\ProductAttribute;

/**
 * @mixin ProductAttribute
 */
class ProductAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'productOptionId' => $this->product_option_id,
            'productOptionChoiceId' => $this->product_option_choice_id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,

            'option' => ProductOptionResource::make($this->whenLoaded('option')),
            'choice' => ProductOptionResource::make($this->whenLoaded('choice')),
        ];
    }
}
