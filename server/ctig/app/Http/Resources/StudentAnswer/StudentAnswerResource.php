<?php

namespace App\Http\Resources\StudentAnswer;

use App\Http\Resources\TaskVariant\TaskVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'studentAnswer' => $this->text_answer,
            'task' => new TaskVariantResource($this->whenLoaded('taskVariant')),
            'id' => $this->id
        ];
    }
}
