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
            'examName' =>$this->whenLoaded('exam', fn () => $this->exam->examType->short_name),
            'answers' =>  AttemptAnswerResource::collection($this->whenLoaded('answers')),
            'expiredAt' => $this->expired_at,
            'solved' => $this->solved,
            'student' => new StudentResource($this->whenLoaded('student')),
            'id' => $this->id,
            'startedAt' => $this->started_at?->format('H:i'),
            'finishedAt' => $this->finished_at?->format('H:i'),
            'date' => $this->finished_at?->format('d.m.Y'),
            'isPassed' => $this->is_passed,
            'status' => $this->status
        ];
    }
}
