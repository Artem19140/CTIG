<?php

namespace App\Http\Resources\ActivityLog;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'event' => $this->resource->event,
            'resource' => $this->resource->resource,
            'context' => $this->resource->context,
            'meta' => $this->resource->meta,
            'actor' => new UserResource($this->whenLoaded('actor')),
            'createdAt' => $this->created_at_local->toIso8601String()
        ];
    }
}
