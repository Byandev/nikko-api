<?php

namespace Modules\Certificate\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Certificate\Models\Certificate;
use Modules\Media\Transformers\MediaResource;

/**
 * @mixin Certificate
 */
class CertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'title' => $this->title,
            'issued_date' => $this->issued_date,
            'reference_id' => $this->reference_id,
            'url' => $this->url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'image' => MediaResource::make($this->whenLoaded('image')),
        ];
    }
}
