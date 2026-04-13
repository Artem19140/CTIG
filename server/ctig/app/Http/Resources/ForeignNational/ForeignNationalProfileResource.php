<?php

namespace App\Http\Resources\ForeignNational;

use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Enrollment\EnrollmentResource;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForeignNationalProfileResource extends JsonResource
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
            'dateBirth' => $this->resource->date_birth->format('Y-m-d H:i:s'),
            'surnameLatin' =>$this->surname_latin,
            'comment' => $this->comment,
            'surname' => $this->resource->surname,
            'name' => $this->resource->name,
            'patronymic' => $this->resource->patronymic,
            'nameLatin' => $this->resource->name_latin,
            'patronymicLatin' =>$this->resource->patronymic_latin,
            'passportNumber' => $this->resource->passport_number,
            'passportSeries' => $this->resource->passport_series,
            'issuedBy' =>$this->resource->issued_by,
            'issuedDate' => $this->issued_date->format('Y-m-d H:i:s'),
            'citizenship' => $this->resource->citizenship,
            'phone' => $this->resource->formatted_phone,
            'gender' =>$this->resource->gender,
            'creator'=>new UserResource($this->whenLoaded('creator')),
            'attempts' => AttemptResource::collection($this->whenLoaded('attempts')),
            'attempt' => new AttemptResource($this->whenLoaded('attempts', fn() => $this->attempts->first())),
            'enrollments' => EnrollmentResource::collection($this->whenLoaded('enrollments')),
            'exams' => ExamResource::collection($this->whenLoaded('exams')),
            'passportScan' => $this->passport_scan,
            'passportTranslateScan' => $this->passport_translate_scan,
            'createdAt' => $this->created_at,
            'fullName' => $this->full_name,
            'fullNameLatin' => $this->full_name_latin,
            'fullPassport' => $this->full_passport,
            'hasPayment' => $this->whenPivotLoaded('enrollments', function () {
                return $this->pivot->has_payment;
            })
        ];
    }
}
