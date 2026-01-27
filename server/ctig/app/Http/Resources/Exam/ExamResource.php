<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Student\StudentResource;

class ExamResource extends JsonResource
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
            'examDate' => $this->start_time,
            'isConducted' => $this->is_conducted, //нужно ли это?! если есть статус
            'students' => StudentResource::collection($this->whenLoaded('students')),//здесь если есть результаты, то и их можно взять
            'sessionNumber' => $this->session_number,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'comment'=>$this->comment,
            'group' => $this->group
        ];
    }
}
