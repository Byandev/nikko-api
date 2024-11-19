<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\Account;
use Modules\Project\Database\Factories\ContractFactory;

class Contract extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'project_id',
        'proposal_id',
        'amount',
        'platform_fee_percentage',
        'total_amount',
        'status',
    ];

    protected static function newFactory(): ContractFactory
    {
        return ContractFactory::new();
    }

    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function proposal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => [
                'amount' => $value,
                'total_amount' => $value * 1.05,
            ],
        );
    }
}
