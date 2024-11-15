<?php

namespace Modules\Project\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\AccountResource;
use Modules\Media\Transformers\MediaResource;
use Modules\Project\Models\Project;
use Modules\Skill\Transformers\SkillResource;

/**
 * @mixin Project
 */
class ProjectResource extends JsonResource
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
            'description' => $this->description,
            'estimated_budget' => $this->estimated_budget,
            'status' => $this->status,
            'length' => $this->length,
            'experience_level' => $this->experience_level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'is_saved' => $this->is_saved ?? false,
            'proposals_count' => $this->proposals_count ?? 0,

            'my_proposal' => ProposalResource::make($this->whenLoaded('myProposal')),
            'account' => AccountResource::make($this->whenLoaded('account')),
            'languages' => ProjectLanguageResource::collection($this->whenLoaded('languages')),
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'images' => MediaResource::collection($this->whenLoaded('images')),
        ];
    }
}
