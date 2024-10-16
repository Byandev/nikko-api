<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Models\User;
use Modules\Media\Transformers\MediaResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'email_verified_at' => $this->email_verified_at,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'state_code' => $this->state_code,
            'country_code' => $this->country_code,
            'postal' => $this->postal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'avatar' => MediaResource::make($this->whenLoaded('avatar')),
            'banner' => MediaResource::make($this->whenLoaded('banner')),
            'accounts' => AccountResource::collection($this->whenLoaded('accounts')),
            'languages' => LanguageResource::collection($this->whenLoaded('languages')),
        ];
    }
}
