<?php

namespace App\Http\Resources\Attempt;

use App\Http\Resources\TaskVariant\TaskVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttemptCheckingResource extends JsonResource
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
            'expiredAt' => $this->expired_at,            
            'status' => $this->status(),
            'tasks' => TaskVariantResource::collection($this->whenLoaded('taskVariants', fn () =>  $this->taskVariants)),
            'checkedAt' => $this->checked_at
        ];
    }
}
