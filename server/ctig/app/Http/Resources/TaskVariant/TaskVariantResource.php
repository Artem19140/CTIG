<?php

namespace App\Http\Resources\TaskVariant;

use App\Http\Resources\Answer\AnswerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'fipiGuid' => $this->fipi_number,
            'groupId' => $this->group_number,
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
            'studentAnswer' => 1
        ];
    }
}
