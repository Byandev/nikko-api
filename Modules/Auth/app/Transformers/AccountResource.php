<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Models\Account;
use Modules\Certificate\Transformers\CertificateResource;
use Modules\Portfolio\Transformers\PortfolioResource;
use Modules\Project\Transformers\ProposalInvitationResource;
use Modules\Skill\Transformers\SkillResource;
use Modules\Tool\Transformers\ToolResource;

/**
 * @mixin Account
 */
class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bio' => $this->bio,
            'type' => $this->type,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'is_saved' => $this->is_saved ?? false,

            'user' => UserResource::make($this->whenLoaded('user')),
            'tools' => ToolResource::collection($this->whenLoaded('tools')),
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'educations' => EducationResource::collection($this->whenLoaded('educations')),
            'portfolios' => PortfolioResource::collection($this->whenLoaded('portfolios')),
            'certificates' => CertificateResource::collection($this->whenLoaded('certificates')),
            'work_experiences' => WorkExperienceResource::collection($this->whenLoaded('workExperiences')),
            'proposal_invitation_to_project' => ProposalInvitationResource::make($this->whenLoaded('proposalInvitationToProject')),
        ];
    }
}
