<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Auth\Models\Account;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Project\Database\Factories\ProposalFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Proposal extends Model implements HasMedia
{
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
        'bid' => 'decimal:8',
        'transaction_fee' => 'decimal:8',
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
}
