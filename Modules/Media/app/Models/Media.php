<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Media\Database\Factories\MediaFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use HasFactory;

    protected static function newFactory(): MediaFactory
    {
        return MediaFactory::new();
    }
}
