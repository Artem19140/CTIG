<?php

namespace App\Http\Resources\Exam;

use App\Domain\Exam\Resolver\ExamStatusResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamIndexResource extends JsonResource
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
            'beginTime' => $this->begin_time_local->toIso8601String(),
            'capacity' => $this->capacity,
            'name' => $this->whenLoaded('type', fn () => $this->type->name),
            'shortName' => $this->whenLoaded('type', fn () => $this->type->short_name),
            'enrollmentsCount' => $this->whenCounted('enrollments_count'),
            'status' => app(ExamStatusResolver::class)->execute($this->resource),
        ];
    }
}
