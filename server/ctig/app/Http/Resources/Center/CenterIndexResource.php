<?php

namespace App\Http\Resources\Center;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CenterIndexResource extends JsonResource
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
            'shortName' => $this->resource->short_name,
            'employeesCount' => $this->resource->employees_count
        ];
    }
}
