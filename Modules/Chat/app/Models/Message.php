<?php

namespace Modules\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Auth\Models\User;
use Modules\Chat\Database\Factories\MessageFactory;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'chat_messages';

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): MessageFactory
    {
        return MessageFactory::new();
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::CHAT_MESSAGE_ATTACHMENTS->value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::CHAT_MESSAGE_ATTACHMENTS->value)
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')->width(254);
            });
    }
}
