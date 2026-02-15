<?php

namespace App\Http\Resources\StudentAnswer;

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
            'studentAnswer' => $this->student_answer,
            'id' => $this->id,
            'examBlockId' => $this->exam_block_id
        ];
    }
}
