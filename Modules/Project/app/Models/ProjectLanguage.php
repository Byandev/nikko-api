<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Project\Database\Factories\LanguageFactory;

class ProjectLanguage extends Model
{
    //    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    // protected static function newFactory(): LanguageFactory
    // {
    //     // return LanguageFactory::new();
    // }
}
