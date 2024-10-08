<?php

namespace Modules\Media\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;

trait HasBanner
{
    public function banner(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::BANNER->value);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the avatar media.
     */
    public function removeBanner(): void
    {
        $this->touch();

        $this->banner()->delete();
    }

    /**
     * Set the avatar using a provided media id.
     */
    public function setBannerByMediaId(int $mediaId)
    {
        $instance = $this;

        $media = Media::findOrFail($mediaId);

        $this->touch();

        $media->move($instance, MediaCollectionType::BANNER->value);

        return $media->fresh();
    }
}
