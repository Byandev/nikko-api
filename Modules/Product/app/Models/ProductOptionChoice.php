<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Database\Factories\ProductOptionChoiceFactory;

class ProductOptionChoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_option_id', 'name', 'deleted_at',
    ];

    protected static function newFactory(): ProductOptionChoiceFactory
    {
        return ProductOptionChoiceFactory::new();
    }
}
