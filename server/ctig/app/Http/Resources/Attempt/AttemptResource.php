<?php

namespace App\Http\Resources\Attempt;

use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\StudentAnswer\StudentAnswerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttemptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'exam' => new ExamResource($this->whenLoaded('exam')),
            'answers' =>  StudentAnswerResource::collection($this->whenLoaded('answers')),
            'id' => $this->id,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at
        ];
    }
}
