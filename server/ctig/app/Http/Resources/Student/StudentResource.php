<?php

namespace App\Http\Resources\Student;
 
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
            'surnameLatin' => $this->resource->surname_latin,
            'nameLatin' => $this->resource->name_latin,
            'patronymicLatin' => $this->resource->patronymic_latin,
            'passportNumber' => $this->resource->surname,
            'passportSeries' => $this->resource->surname,
            'issuedBy' => $this->resource->issued_by,
            'issuesDate' => $this->resource->issues_date,
            'addressReg' => $this->resource->address_reg,
            'migrationCardRequisite' => $this->resource->migration_card_requisite,
            'citizenship' => $this->resource->citizenship,
            'phone' => $this->resource->phone,
            'creator'=>new UserResource($this->whenLoaded('creator')),
            'createdAt' => $this->created_at
        ];
    }
}
