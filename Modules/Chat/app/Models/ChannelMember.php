<?php

namespace Modules\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Chat\Database\Factories\ChannelMemberFactory;

class ChannelMember extends Model
{
    use HasFactory;

    protected $table = 'chat_channel_members';

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): ChannelMemberFactory
    {
        return ChannelMemberFactory::new();
    }
}
