<?php

namespace App\Http\Resources\Exam;

use App\Http\Resources\Enrollment\EnrollmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamCheckingResource extends JsonResource
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
            'beginTime' =>$this->begin_time_local->copy()->toIso8601String(),
            'shortName' => $this->whenLoaded('type', fn () => $this->type->short_name),
            'enrollments' => EnrollmentResource::collection($this->whenLoaded('enrollments')),
        ];
    }
}
