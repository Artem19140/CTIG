<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\TaskVariant\TaskVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'order' => $this->order,
            'variants' =>  TaskVariantResource::collection($this->whenLoaded('variants'))
        ];
    }
}
