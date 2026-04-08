<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamCalendarResource extends JsonResource
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
            'isPast' =>  $this->begin_time_utc->copy()->addMinutes($this->duration)->isPast(),
            'start' => $this->begin_time->copy()->format('Y-m-d H:i'), //
            'end' => $this->begin_time->copy()->addMinutes($this->duration)->format('Y-m-d H:i'),
            'name' => $this->whenLoaded('examType', fn () => $this->examType->short_name),
            'isGoing' => $this->isGoing(),
        ];
    }
}
