<?php

namespace App\Http\Resources\ExamAttempt;

use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\Student\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamAttemptResource extends JsonResource
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
            'id' => $this->id
            //'answers' =>  StudentAn::collection($this->whenLoaded('answers'))
        ];
    }
}
