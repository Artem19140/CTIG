<?php

namespace App\Http\Resources\ForeignNational;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForeignNationalIndexResource extends JsonResource
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
            'patronymic' => $this->resource->patronymic,
            'fullName' => $this->full_name_short,
            'fullPassport' => $this->full_passport,
        ];
    }
}
