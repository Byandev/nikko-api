<?php

namespace Modules\Media\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;

trait HasAvatar
{
    use InteractsWithMedia;

    public function avatar(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::AVATAR->value);
    }

    /*
    |--------------------------------------------------------------------------
    | Media Collections
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Remove the avatar media.
     */
    public function removeAvatar(): void
    {
        $this->touch();

        $this->avatar()->delete();
    }

    /**
     * Set the avatar using a provided media id.
     */
    public function setAvatarByMediaId(int $mediaId)
    {
        $instance = $this;

        $media = Media::findOrFail($mediaId);

        $this->touch();

        $media->move($instance, MediaCollectionType::AVATAR->value);

        return $media->fresh();
    }
}
