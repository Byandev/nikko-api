<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Modules\Auth\Database\Factories\UserFactory;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Traits\HasAvatar;
use Modules\Media\Models\Traits\HasBanner;
use Spatie\MediaLibrary\HasMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasAvatar, HasBanner, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::AVATAR->value)
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')->width(254);
            });

        $this->addMediaCollection(MediaCollectionType::BANNER->value)
            ->singleFile();
    }

    public function generateEmailVerificationCode(): string
    {
        $code = '000000'; // Str::random(6);

        Cache::put("user:$this->id:email:verification:code", $code, now()->addMinutes(5));

        return $code;
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function passwordReset(): HasOne
    {
        return $this->hasOne(PasswordReset::class);
    }

    public function changedRequests(): MorphMany
    {
        return $this->morphMany(ChangeRequest::class, 'changeable');
    }

    public function languages(): HasMany
    {
        return $this->hasMany(Language::class);
    }

    public function scopeSearch(Builder $builder, string $search)
    {
        $builder->where(function (Builder $query) use ($search) {
            $query->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('first_name', 'LIKE', "%$search%")
                ->orWhere('last_name', 'LIKE', "%$search%")
                ->orWhere('phone_number', 'LIKE', "%$search%");
        });
    }

    public function scopeCountries(Builder $builder, ...$countries)
    {
        $builder->when(count($countries), function (Builder $query) use ($countries) {
            $query->whereIn('country_code', $countries);
        });
    }
}
