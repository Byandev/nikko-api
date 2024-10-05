<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Models\PasswordReset;

/**
 * @mixin PasswordReset
 */
class PasswordResetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'expires_at' => $this->expires_at,
        ];
    }
}
