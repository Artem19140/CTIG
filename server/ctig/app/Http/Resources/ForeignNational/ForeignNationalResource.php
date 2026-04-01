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
            'surnameLatin' => $this->when($profile,$this->surname_latin),
            'comment' => $this->when($profile,$this->comment),
            'nameLatin' => $this->when($profile,$this->resource->name_latin),
            'patronymicLatin' => $this->when($profile,$this->resource->patronymic_latin),
            'passportNumber' => $this->when($profile,$this->resource->passport_number),
            'passportSeries' => $this->when($profile,$this->resource->passport_series),
            'issuedBy' => $this->when($profile,$this->resource->issued_by),
            'issuedDate' => $this->when($profile,$this->issued_date->format('Y-m-d H:i:s')),
            'addressReg' => $this->when($profile,$this->resource->address_reg),
            'migrationCardRequisite' => $this->when($profile,$this->resource->migration_card_requisite),
            'citizenship' => $this->when($profile,$this->resource->citizenship),
            'phone' => $this->when($profile,$this->resource->formatted_phone),
            'gender' => $this->when($profile,$this->resource->gender),
            'creator'=>new UserResource($this->whenLoaded('creator')),
            'attempts' => AttemptResource::collection($this->whenLoaded('attempts')),
            'exams' => ExamResource::collection($this->whenLoaded('exams')),
            'passportScanPath' => $this->when($profile,$this->passport_scan_path),
            'passportTranslateScanPath' => $this->when($profile,$this->passport_translate_scan),
            'photo' => $this->when($profile,$this->photo_path),
            'createdAt' => $this->when($profile,$this->created_at),
            'fullName' => $this->full_name,
            'fullPassport' => $this->full_passport,
            'hasPayment' => $this->whenPivotLoaded('exam_foreign_national', function () {
                return $this->pivot->has_payment;
            })
        ];
    }
}
