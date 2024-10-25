<?php

namespace Modules\Portfolio\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Auth\Models\Account;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Portfolio\Database\Factories\PortfolioFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Portfolio extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'title',
        'description',
        'url',
    ];

    protected static function newFactory(): PortfolioFactory
    {
        return PortfolioFactory::new();
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::PORTFOLIO_IMAGES->value);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::PORTFOLIO_IMAGES->value)
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')->width(254);
            });
    }
}
