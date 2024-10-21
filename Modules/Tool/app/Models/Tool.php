<?php

namespace Modules\Tool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Tool\Database\Factories\ToolFactory;

class Tool extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    protected static function newFactory(): ToolFactory
    {
        return ToolFactory::new();
    }
}
