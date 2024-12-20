<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Category\Models\Category;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Product\Database\Factories\ProductFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'title',
        'description',
        'price',
        'is_active',
        'deleted_at',
    ];

    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id', 'id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::PRODUCT_ATTACHMENTS->value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::PRODUCT_ATTACHMENTS->value)
            ->registerMediaConversions(function () {
                if (config('app.env') !== 'testing') {
                    $this->addMediaConversion('thumb')->width(254);
                }
            });
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorable', 'categorizables');
    }
}
