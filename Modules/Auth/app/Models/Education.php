<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Database\Factories\EducationFactory;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'degree',
        'country',
        'description',
        'start_month',
        'start_year',
        'end_month',
        'end_year',
    ];

    protected static function newFactory(): EducationFactory
    {
        return EducationFactory::new();
    }
}
