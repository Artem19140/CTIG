<?php

namespace App\Http\Resources\Student;
 
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'dateBirth' => $this->resource->date_birth,
            'surnameLatin' => $this->when($request->routeIs('students.show'),$this->resource->surname_latin),
            'nameLatin' => $this->when($request->routeIs('students.show'),$this->resource->name_latin),
            'patronymicLatin' => $this->when($request->routeIs('students.show'),$this->resource->patronymic_latin),
            'passportNumber' => $this->when($request->routeIs('students.show'),$this->resource->passport_number),
            'passportSeries' => $this->when($request->routeIs('students.show'),$this->resource->passport_series),
            'issuedBy' => $this->when($request->routeIs('students.show'),$this->resource->issued_by),
            'issuedDate' => $this->when($request->routeIs('students.show'),$this->resource->issued_date),
            'addressReg' => $this->when($request->routeIs('students.show'),$this->resource->address_reg),
            'migrationCardRequisite' => $this->when($request->routeIs('students.show'),$this->resource->migration_card_requisite),
            'citizenship' => $this->when($request->routeIs('students.show'),$this->resource->citizenship),
            'phone' => $this->when($request->routeIs('students.show'),$this->resource->phone),
            'creator'=>new UserResource($this->whenLoaded('creator')),
            'attempts' => AttemptResource::collection($this->whenLoaded('attempts')),
            //'attempts' => $this->whenLoaded('attempts',$this->attempts->first()),
            'exams' => ExamResource::collection($this->whenLoaded('exams')),
            'passportScan' => $this->when($request->routeIs('students.show'),$this->passport_scan_path),
            'photo' => $this->when($request->routeIs('students.show'),$this->photo_path),
            'createdAt' => $this->when($request->routeIs('students.show'),$this->created_at),
            'fullName' => $this->full_name,
            'fullPassport' => $this->full_passport
        ];
    }
}
