<?php

namespace Modules\Chat\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\UserResource;
use Modules\Chat\Models\Channel;
use Modules\Project\Transformers\ProposalResource;

/**
 * @mixin Channel
 */
class ChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject_id' => $this->subject_id,
            'subject_type' => $this->subject_type,
            'title' => $this->title,
            'last_activity_at' => $this->last_activity_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'subject' => ProposalResource::make($this->whenLoaded('subject')),
            'members' => UserResource::collection($this->whenLoaded('members')),
        ];
    }
}
