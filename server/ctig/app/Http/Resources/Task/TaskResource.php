<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Answer\AnswerResource;

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
            'contain' => $this->resource->contain,
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
            'fipiGuid' => $this->fipi_guid
        ];
    }
}
