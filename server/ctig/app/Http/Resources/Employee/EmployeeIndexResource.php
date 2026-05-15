<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->resource->id,
            'fullName' => $this->resource->surname.' '.$this->resource->name.' '.$this->resource->patronymic,
            'email'=>$this->resource->email,
            'jobTitle' => $this->job_title,
        ];
    }
}
