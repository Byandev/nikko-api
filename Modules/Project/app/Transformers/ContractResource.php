<?php

namespace Modules\Project\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\AccountResource;
use Modules\Project\Models\Contract;

/**
 * @mixin Contract
 */
class ContractResource extends JsonResource
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
            'proposal_id' => $this->proposal_id,
            'amount' => $this->amount,
            'platform_fee_percentage' => $this->platform_fee_percentage,
            'total_amount' => $this->total_amount,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'account' => AccountResource::make($this->whenLoaded('account')),
            //            'project' => ProjectResource::make($this->whenLoaded('project')),
            'proposal' => ProposalResource::make($this->whenLoaded('proposal')),

        ];
    }
}
