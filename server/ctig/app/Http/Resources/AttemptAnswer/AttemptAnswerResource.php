<?php

namespace App\Http\Resources\AttemptAnswer;

use App\Http\Resources\TaskVariant\TaskVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttemptAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'attemptAnswer' => $this->answer,
            'task' => new TaskVariantResource($this->whenLoaded('taskVariant')),
            'id' => $this->id
        ];
    }
}
