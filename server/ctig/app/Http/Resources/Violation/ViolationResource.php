<?php

namespace App\Http\Resources\Violation;

use App\Support\TimePresenter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViolationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'createdAt' => TimePresenter::forCenter($this->created_at, $this->attempt->center)->toIso8601String()
        ];
    }
}
