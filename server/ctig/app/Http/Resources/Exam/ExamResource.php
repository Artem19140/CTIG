<?php

namespace App\Http\Resources\Exam;

use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\ExamAttempt\ExamAttemptResource;
use App\Http\Resources\ExamType\ExamTypeResource;
use App\Http\Resources\User\UserResource;
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
            'beginTime' => $this->begin_time,
            'endTime' => $this->end_time,
            'students' => StudentResource::collection($this->whenLoaded('students')),//здесь если есть результаты, то и их можно взять
            'sessionNumber' => $this->session_number,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'comment'=>$this->comment,
            'group' => $this->group,
            'testers' => UserResource::collection($this->whenLoaded('testers')),
            'name' => new ExamTypeResource($this->whenLoaded('examType')),
            'address' => new AddressResource($this->whenLoaded('address')),
            'creator'=> new UserResource($this->whenLoaded('creator')),
            'createdAt' => $this->created_at
        ];
    }
}
