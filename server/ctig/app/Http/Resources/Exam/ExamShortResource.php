<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamShortResource extends JsonResource
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
            'examTypeId' => $this->whenLoaded('examType', fn () => $this->examType->id),
            'shortName' => $this->whenLoaded('examType', fn () => $this->examType->short_name),
            'beginTime' => $this->begin_time->copy()->format('Y-m-d H:i:s'),
        ];
    }
}
