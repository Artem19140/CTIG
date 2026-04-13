<?php

namespace App\Http\Resources\ForeignNational;
 
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Enrollment\EnrollmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForeignNationalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'surname' => $this->resource->surname,
            'name' => $this->resource->name,
            'patronymic' => $this->resource->patronymic,
            'fullName' => $this->full_name_short,
            'fullPassport' => $this->full_passport,
            'attempts' => AttemptResource::collection($this->whenLoaded('attempts')),
            'attempt' => new AttemptResource($this->whenLoaded('attempts', fn() => $this->attempts->first())),
            'enrollments' => EnrollmentResource::collection($this->whenLoaded('enrollments')),
        ];
    }
}
