<?php

namespace App\Http\Resources\ForeignNational;
 
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\User\UserResource;
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
        $profile = $request->boolean('profile');
        return [
            'id' => $this->resource->id,
            'surname' => $this->resource->surname,
            'name' => $this->resource->name,
            'patronymic' => $this->resource->patronymic,
            'dateBirth' => $this->resource->date_birth->format('Y-m-d H:i:s'),
            'fullName' => $this->full_name_short,
            'fullPassport' => $this->full_passport,
            'hasPayment' => $this->whenPivotLoaded('enrollments', function () {
                return $this->pivot->has_payment;
            })
        ];
    }
}
