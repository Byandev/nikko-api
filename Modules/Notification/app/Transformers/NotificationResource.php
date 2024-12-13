<?php

namespace Modules\Notification\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

/**
 * @mixin DatabaseNotification
 */
class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @throws ReflectionException
     */
    public function toArray(Request $request): array
    {
        $type = (new ReflectionClass($this->type))->getShortName();
        $type = Str::of($type)->snake()->value();

        return [
            'id' => $this->id,
            'type' => $type,
            'notifiable_id' => $this->notifiable_id,
            'message' => Arr::get($this->data, 'message'),
            'read_at' => $this->read_at,
            'read' => (bool) $this->read_at,
            'created_at' => $this->created_at,
        ];
    }
}
