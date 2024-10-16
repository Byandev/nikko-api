<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Database\Factories\WorkExperienceFactory;

class WorkExperience extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'job_title',
        'company',
        'website',
        'country',
        'employment',
        'description',
        'start_month',
        'start_year',
        'end_month',
        'end_year',
        'is_current',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    protected static function newFactory(): WorkExperienceFactory
    {
        return WorkExperienceFactory::new();
    }
}
