<?php

namespace Modules\Project\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\AccountResource;
use Modules\Project\Models\ProposalInvitation;

/**
 * @mixin ProposalInvitation
 */
class ProposalInvitationResource extends JsonResource
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
            'message' => $this->message,
            'rejection_message' => $this->rejection_message,
            'status' => $this->status,

            'account' => AccountResource::make($this->whenLoaded('account')),
            'project' => ProjectResource::make($this->whenLoaded('project')),
        ];
    }
}
