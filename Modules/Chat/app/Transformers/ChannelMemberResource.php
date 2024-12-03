<?php

namespace Modules\Chat\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\UserResource;
use Modules\Chat\Models\ChannelMember;

/**
 * @mixin ChannelMember
 */
class ChannelMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'channel_id' => $this->channel_id,
            'model_id' => $this->model_id,
            'model_type' => $this->model_type,
            'last_read_at' => $this->last_read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'model' => UserResource::make($this->whenLoaded('model')),
        ];
    }
}
