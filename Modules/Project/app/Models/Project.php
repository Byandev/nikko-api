<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Project\Database\Factories\ProjectFactory;
use Modules\Skill\Models\Skill;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'title',
        'description',
        'estimated_budget',
        'length',
        'experience_level',
    ];

    protected static function newFactory(): ProjectFactory
    {
        return ProjectFactory::new();
    }

    public function languages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProjectLanguage::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'project_skills');
    }
}
