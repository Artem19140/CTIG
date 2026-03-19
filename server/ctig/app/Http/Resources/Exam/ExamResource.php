<?php

namespace App\Http\Resources\Exam;

use App\Http\Resources\Attempt\AttemptResource;
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
            'isCancelled' => $this->is_cancelled,
            'beginTime' => $this->begin_time,
            'students' => StudentResource::collection($this->whenLoaded('students')),//здесь если есть результаты, то и их можно взять
            'sessionNumber' => $this->session,
            'capacity' => $this->capacity,
            'comment'=>$this->comment,
            'group' => $this->group,
            'examiners' => UserResource::collection($this->whenLoaded('examiners')),
            'name' => $this->whenLoaded('examType', fn () => $this->examType->name),
            'shortName' => $this->whenLoaded('examType', fn () => $this->examType->short_name),
            'address' => $this->whenLoaded('address', fn () =>$this->address->address),
            'creator'=> new UserResource($this->whenLoaded('creator')),
            'createdAt' => $this->created_at,
            'studentsCount' => $this->whenCounted('students_count'),
            'attempts' => AttemptResource::collection( $this->whenLoaded('attempts')),
            'duration' => $this->whenLoaded('examType', fn () => $this->examType->duration),
            'endTime' => $this->end_time,
            'start' => $this->begin_time->format('Y-m-d H:i'), 
            'end' => $this->begin_time->addMinutes($this->duration)->format('Y-m-d H:i'),
            'isPast' =>  $this->begin_time->addMinutes($this->duration)->isPast(),
            'tasksCount' => $this->whenLoaded('examType', fn () => $this->examType->tasks_count),
            'hasSpeakingTasks' => $this->whenLoaded('examType', fn () => $this->examType->has_speaking_tasks),
        ];
    }
}
