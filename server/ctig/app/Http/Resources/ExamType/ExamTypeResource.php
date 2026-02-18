<?php

namespace App\Http\Resources\ExamType;

use App\Http\Resources\Block\BlockResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ExamTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->resource->id,
            'name' => $this->resource->name,
            'blocks' => BlockResource::collection($this->whenLoaded('blocks'))
        ];
    }
}
