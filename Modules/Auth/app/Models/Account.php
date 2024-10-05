<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Database\Factories\AccountFactory;

// use Modules\Auth\Database\Factories\AccountFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    protected static function newFactory(): AccountFactory
    {
        return AccountFactory::new();
    }
}
