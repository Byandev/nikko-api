<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Database\Factories\ChangeRequestFactory;

class ChangeRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['field_name', 'from', 'to', 'token'];

    protected static function newFactory(): ChangeRequestFactory
    {
        return ChangeRequestFactory::new();
    }

    public function changeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isTokenValid($token): bool
    {
        return Hash::check($token, $this->token);
    }
}
