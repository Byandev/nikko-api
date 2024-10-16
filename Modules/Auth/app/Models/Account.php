<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Database\Factories\AccountFactory;

// use Modules\Auth\Database\Factories\AccountFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'bio',
    ];

    protected static function newFactory(): AccountFactory
    {
        return AccountFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
