<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Auth\Models\Account;
use Modules\Chat\Models\Channel;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Project\Database\Factories\ProposalFactory;
use Modules\Save\Models\Traits\CanBeSaved;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Proposal extends Model implements HasMedia
{
    use CanBeSaved;
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'project_id',
        'bid',
        'transaction_fee',
        'length',
        'status',
        'cover_letter',
    ];

    protected $casts = [
        'bid' => 'float',
        'transaction_fee' => 'float',
    ];

    protected static function newFactory(): ProposalFactory
    {
        return ProposalFactory::new();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::PROPOSAL_ATTACHMENTS->value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::PROPOSAL_ATTACHMENTS->value)
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')->width(254);
            });
    }

    public function contract(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Contract::class);
    }

    public function scopeAppendProposalsCount(Builder $query)
    {
        $query->addSelect([
            'project_proposals_count' => Proposal::selectRaw('COUNT(id) as project_proposals_count')
                ->whereColumn($this->qualifyColumn('project_id'), $this->qualifyColumn('project_id'))
                ->take(1),
        ]);

        $query->withCasts(['project_proposals_count' => 'int']);
    }

    public function chat_channel(): MorphOne
    {
        return $this->morphOne(Channel::class, 'subject');
    }
}
