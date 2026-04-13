<?php

namespace App\Http\Resources\Enrollment;

use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\ForeignNational\ForeignNationalResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
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
            'foreignNational' => new ForeignNationalResource($this->whenLoaded('foreignNational')),
            'hasPayment' => $this->has_payment,
            'exam' => new ExamResource($this->whenLoaded('exam')),
            'attempt' => new AttemptResource($this->whenLoaded('attempt'))
        ];
    }
}
