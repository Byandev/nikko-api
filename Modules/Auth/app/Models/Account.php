<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Auth\Database\Factories\AccountFactory;
use Modules\Auth\Enums\AccountType;
use Modules\Certificate\Models\Certificate;
use Modules\Portfolio\Models\Portfolio;
use Modules\Project\Enums\ContractStatus;
use Modules\Project\Models\Contract;
use Modules\Project\Models\Project;
use Modules\Project\Models\Proposal;
use Modules\Project\Models\ProposalInvitation;
use Modules\Save\Models\Traits\CanBeSaved;
use Modules\Skill\Models\Skill;
use Modules\Tool\Models\Tool;

class Account extends Model
{
    use CanBeSaved;
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'bio',
    ];

    protected static function newFactory(): AccountFactory
    {
        return AccountFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'account_skill');
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'account_tool');
    }

    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function proposalInvitations(): HasMany
    {
        return $this->hasMany(ProposalInvitation::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function proposalInvitationToProject(): HasOne
    {
        return $this->hasOne(ProposalInvitation::class)
            ->when(request()->input('proposal_invitation_to_project'), function (Builder $query) {
                $query->where('project_id', request()->input('proposal_invitation_to_project'));
            }, function (Builder $query) {
                $query->whereNull('proposal_invitation_to_project');
            });
    }

    public function scopeSearch(Builder $builder, string $search)
    {
        return $builder->where(function (Builder $query) use ($search) {
            $query->where('title', 'LIKE', "%$search%")
                ->orWhere('bio', 'LIKE', "%$search%")
                ->orWhereHas('user', fn (Builder $subQuery) => $subQuery->search($search));
        });
    }

    public function scopeType(Builder $builder, string $type)
    {
        return $builder->where('type', $type);
    }

    public function scopeSkills(Builder $builder, ...$skillIds)
    {
        $builder->when(count($skillIds), function (Builder $query) use ($skillIds) {
            $query->whereHas('skills', function (Builder $subQuery) use ($skillIds) {
                $subQuery->whereIn('id', $skillIds);
            });
        });
    }

    public function scopeUserCountries(Builder $builder, ...$countries)
    {
        $builder->whereHas('user', function (Builder $query) use ($countries) {
            $query->whereIn('country_code', $countries);
        });
    }

    public function scopeAppendTotalEarnings(Builder $builder)
    {
        $builder->addSelect([
            'total_earnings' => Contract::selectRaw("CASE WHEN `accounts`.`type` = 'FREELANCER' AND SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END")
                ->where('status', ContractStatus::COMPLETED->value)
                ->whereColumn((new Contract)->qualifyColumn('account_id'), $this->qualifyColumn('id')),
        ]);

        $builder->withCasts(['total_earnings' => 'double']);
    }

    public function scopeAppendTotalSpent(Builder $builder)
    {
        $builder->addSelect([
            'total_spent' => Contract::selectRaw("CASE WHEN `accounts`.`type` = 'CLIENT' AND SUM(amount) IS NOT NULL THEN SUM(amount) ELSE 0 END")
                ->where('status', ContractStatus::COMPLETED->value)
                ->whereHas('project', function (Builder $query) {
                    $query->whereColumn((new Project)->qualifyColumn('account_id'), $this->qualifyColumn('id'));
                }),
        ]);

        $builder->withCasts(['total_spent' => 'double']);
    }

    public function getTotalEarnings()
    {
        return $this->type === AccountType::FREELANCER->value ?
            Contract::where('account_id', $this->id)
                ->whereStatus(ContractStatus::COMPLETED->value)
                ->sum('amount') :
            0;
    }

    public function getTotalSpent()
    {
        return $this->type === AccountType::CLIENT->value ?
            Contract::whereStatus(ContractStatus::COMPLETED->value)
                ->whereHas('project', fn (Builder $builder) => $builder->where('account_id', $this->id))
                ->sum('amount') :
            0;
    }
}
