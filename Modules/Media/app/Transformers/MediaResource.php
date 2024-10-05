<?php

namespace Modules\Media\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Media\Models\Media;

/**
 * @mixin Media
 */
class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type,
            'disk' => $this->disk,
            'size' => $this->size,
            'conversions_disk' => $this->conversions_disk,
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'created_at' => $this->created_at,
            'order_column' => $this->order_column,
            'updated_at' => $this->updated_at,
            'custom_properties' => $this->custom_properties,
            'manipulations' => $this->manipulations,
            'preview_url' => $this->preview_url,
            'file_name' => $this->file_name,
            'generated_conversions' => $this->generated_conversions,
            'collection_name' => $this->collection_name,
            'original_url' => $this->original_url,
            'responsive_images' => $this->responsive_images,
            'humanReadableSize' => $this->humanReadableSize,
        ];
    }
}
