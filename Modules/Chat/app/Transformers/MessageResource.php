<?php

namespace Modules\Chat\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Transformers\UserResource;
use Modules\Chat\Models\Message;
use Modules\Media\Transformers\MediaResource;

/**
 * @mixin Message
 */
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'channel_id' => $this->channel_id,
            'sender_id' => $this->sender_id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sent_by_me' => Auth::check() && $this->sender_id == Auth::id(),

            'sender' => UserResource::make($this->whenLoaded('sender')),
            'attachments' => MediaResource::collection($this->whenLoaded('attachments')),
        ];
    }
}
