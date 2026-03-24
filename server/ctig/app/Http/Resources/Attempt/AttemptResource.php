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
            //'exam' => new ExamResource($this->whenLoaded('exam')),
            'examName' =>$this->whenLoaded('exam', $this->exam->examType->short_name),
            'answers' =>  AttemptAnswerResource::collection($this->whenLoaded('answers')),
            'expiredAt' => $this->expired_at,
            'solved' => $this->solved,
            'student' => new StudentResource($this->whenLoaded('student')),
            'id' => $this->id,
            'startedAt' => $this->started_at ? $this->started_at->format('d.m.Y') : $this->started_at,
            'finishedAt' => $this->finished_at,
            //'date' => $this->finished_at->format('d.m.Y'),
            'isPassed' => $this->is_passed,
            'status' => $this->status
        ];
    }
}
