<?php

namespace App\Http\Resources\Enrollment;

use App\Domain\Exam\Resolver\ExamResultResolver;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Exam\ExamShortResource;
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
            'exam' => new ExamShortResource($this->whenLoaded('exam')),
            'attempt' => new AttemptResource($this->whenLoaded('attempt')),
            'examResult' => $this->when(
                                        $this->relationLoaded('exam') && 
                                        $this->relationLoaded('attempt'),
                                        fn () => app(ExamResultResolver::class)->execute($this->resource)
                                    )
        ];
    }
}
