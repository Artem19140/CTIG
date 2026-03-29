<?php

namespace App\Http\Resources\Exam;

use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ForeignNational\ForeignNationalResource;

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
            'beginTime' => $this->begin_time->format('H:i, d.m.Y'),
            'foreignNationals' => ForeignNationalResource::collection($this->whenLoaded('foreignNationals')),//здесь если есть результаты, то и их можно взять
            'sessionNumber' => $this->session,
            'capacity' => $this->capacity,
            'comment'=>$this->comment,
            'group' => $this->group,
            'examiners' => UserResource::collection($this->whenLoaded('examiners')),
            'name' => $this->whenLoaded('examType', fn () => $this->examType->name),
            'shortName' => $this->whenLoaded('examType', fn () => $this->examType->short_name),
            'examTypeId' => $this->whenLoaded('examType', fn () => $this->examType->id),
            'address' => $this->whenLoaded('address', fn () =>$this->address->address),
            'creator'=> new UserResource($this->whenLoaded('creator')),
            'createdAt' => $this->created_at,
            'foreignNationalsCount' => $this->whenCounted('foreignNationals_count'),
            'attempts' => AttemptResource::collection( $this->whenLoaded('attempts')),
            'duration' => $this->whenLoaded('examType', fn () => $this->examType->duration),
            'endTime' => $this->end_time,
            'start' => $this->begin_time->format('Y-m-d H:i'), 
            'end' => $this->begin_time->addMinutes($this->duration)->format('Y-m-d H:i'),
            'isPast' =>  $this->begin_time_utc->addMinutes($this->duration)->isPast(),
            'tasksCount' => $this->whenLoaded('examType', fn () => $this->examType->tasks_count),
            'hasSpeakingTasks' => $this->whenLoaded('examType', fn () => $this->examType->has_speaking_tasks),
            'isGoing' => now()->between(
                $this->begin_time_utc,
                $this->begin_time_utc->copy()->addMinutes($this->examType->duration)
            ),
        ];
    }
}
