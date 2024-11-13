<?php

namespace Modules\Project\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\AccountResource;
use Modules\Media\Transformers\MediaResource;
use Modules\Project\Models\Proposal;

/**
 * @mixin Proposal
 */
class ProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'project_id' => $this->project_id,
            'bid' => $this->bid,
            'transaction_fee' => $this->transaction_fee,
            'length' => $this->length,
            'status' => $this->status,
            'cover_letter' => $this->cover_letter,

            'account' => AccountResource::make($this->whenLoaded('account')),
            'project' => ProjectResource::make($this->whenLoaded('project')),
            'attachments' => MediaResource::collection($this->whenLoaded('attachments')),
        ];
    }
}
