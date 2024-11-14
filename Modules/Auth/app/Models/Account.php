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
use Modules\Certificate\Models\Certificate;
use Modules\Portfolio\Models\Portfolio;
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

    public function proposalInvitationToProject(): HasOne
    {
        return $this->hasOne(ProposalInvitation::class)
            ->when(request()->input('project_id'), function (Builder $query) {
                $query->where('project_id', request()->input('project_id'));
            }, function (Builder $query) {
                $query->whereNull('project_id');
            });
    }

    public function scopeSearch(Builder $builder, string $search)
    {
        $builder->where(function (Builder $query) use ($search) {
            $query->where('title', 'LIKE', "%$search%")
                ->orWhere('bio', 'LIKE', "%$search%")
                ->orWhereHas('user', fn (Builder $subQuery) => $subQuery->search($search));
        });
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
}
