<?php

namespace App\Http\Resources\Attempt;

use App\Http\Resources\ForeignNational\ForeignNationalResource;
use App\Http\Resources\TaskVariant\TaskVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Log;

class AttemptExamResource extends JsonResource
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
            'endsAt' => $this->expired_at->timestamp,
            'serverNow' =>now()->timestamp,
            'tasks' => TaskVariantResource::collection($this->whenLoaded('taskVariants', fn () =>  $this->taskVariants)),
            'expiredAt' => $this->resource->expired_at,
            'startedAt' => $this->started_at,
            'finishedAt' => $this->finished_at,
            'foreignNational' => new ForeignNationalResource($this->whenLoaded('foreignNational')),
            'examName' =>$this->whenLoaded('exam', fn () => $this->exam->type->short_name)
        ];
    }
}
