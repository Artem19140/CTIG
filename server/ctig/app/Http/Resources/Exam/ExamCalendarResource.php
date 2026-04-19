<?php

namespace App\Http\Resources\Exam;

use App\Domain\Exam\Resolver\ExamStatusResolver;
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
            'start' => $this->begin_time->copy()->format('Y-m-d H:i'),
            'end' => $this->begin_time->copy()->addMinutes($this->duration)->format('Y-m-d H:i'),
            'name' => $this->whenLoaded('examType', fn () => $this->examType->short_name),
            'status' => app(ExamStatusResolver::class)->execute($this->resource)
        ];
    }
}
