<?php

namespace Modules\Certificate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Auth\Models\Account;
use Modules\Certificate\Database\Factories\CertificateFactory;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Certificate extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'title',
        'issued_date',
        'reference_id',
        'url',
    ];

    protected static function newFactory(): CertificateFactory
    {
        return CertificateFactory::new();
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::CERTIFICATE_IMAGE->value);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
