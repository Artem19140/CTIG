<?php

namespace App\Http\Resources\Attempt;

use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\AttemptAnswer\AttemptAnswerResource;
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
            'answers' =>  AttemptAnswerResource::collection($this->whenLoaded('answers')),
            'expiredAt' => $this->expired_at,
            //'solved' => $this->whenCounted(''),
            'student' => new StudentResource($this->whenLoaded('student')),
            'id' => $this->id,
            'startedAt' => $this->started_at,
            'finishedAt' => $this->finished_at,
            'isPassed' => $this->is_passed,
            'status' => $this->status
        ];
    }
}
