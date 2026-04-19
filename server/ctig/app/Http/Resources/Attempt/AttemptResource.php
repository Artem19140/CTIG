<?php

namespace App\Http\Resources\Attempt;

use App\Domain\Attempt\Resolver\AttemptStatusResolver;
use App\Http\Resources\ForeignNational\ForeignNationalResource;
use App\Http\Resources\AttemptAnswer\AttemptAnswerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttemptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'examName' =>$this->whenLoaded('exam', fn () => $this->exam->type->short_name),
            'answers' =>  AttemptAnswerResource::collection($this->whenLoaded('answers')),
            'expiredAt' => $this->expired_at,
            'solved' => $this->solved,
            'foreignNational' => new ForeignNationalResource($this->whenLoaded('foreignNational')),
            'id' => $this->id,
            'startedAt' => $this->started_at,
            'finishedAt' => $this->finished_at,
            'isPassed' => $this->is_passed,
            'status' => app(AttemptStatusResolver::class)->execute($this->resource),

        ];
    }
}
