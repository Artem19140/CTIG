<?php

namespace App\Http\Resources\Exam;

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
            'date'=> $this->date,
            'endTime' => $this->end_time,
            'students' => StudentResource::collection($this->whenLoaded('students')),//здесь если есть результаты, то и их можно взять
            'sessionNumber' => $this->session,
            'capacity' => $this->capacity,
            'comment'=>$this->comment,
            'group' => $this->group,
            'testers' => UserResource::collection($this->whenLoaded('testers')),
            'name' => $this->whenLoaded('examType', fn () => $this->examType->name),
            'address' => $this->whenLoaded('address', fn () =>$this->address->address),
            'creator'=> new UserResource($this->whenLoaded('creator')),
            'createdAt' => $this->created_at
        ];
    }
}
