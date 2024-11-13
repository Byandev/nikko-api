<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Auth\Models\Account;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Project\Database\Factories\ProjectFactory;
use Modules\Save\Models\Traits\CanBeSaved;
use Modules\Skill\Models\Skill;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use CanBeSaved;
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'title',
        'description',
        'estimated_budget',
        'length',
        'experience_level',
        'status',
    ];

    protected static function newFactory(): ProjectFactory
    {
        return ProjectFactory::new();
    }

    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProjectLanguage::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'project_skills');
    }

    public function myProposal(): HasOne
    {
        return $this->hasOne(Proposal::class)
            ->when(request()->account, function (Builder $query) {
                $query->where('account_id', request()->account->id);
            }, function (Builder $query) {
                $query->whereNull('account_id');
            });
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::PROJECT_IMAGES->value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::PROJECT_IMAGES->value)
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')->width(254);
            });
    }

    public function scopeSearch(Builder $builder, string $search)
    {
        $builder->where(function (Builder $query) use ($search) {
            $query->where('title', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%")
                ->orWhereHas('account', fn (Builder $subQuery) => $subQuery->search($search));
        });
    }
}
