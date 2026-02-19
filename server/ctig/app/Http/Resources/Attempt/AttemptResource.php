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
            'examName' => new ExamResource($this->whenLoaded('exam')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'answers' => new StudentAnswerResource($this->whenLoaded('students')),
            'id' => $this->id
        ];
    }
}
