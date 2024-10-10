<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Database\Factories\ChangeRequestFactory;

class ChangeRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['from', 'to', 'token'];

    protected static function newFactory(): ChangeRequestFactory
    {
        return ChangeRequestFactory::new();
    }
}
