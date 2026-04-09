<?php

namespace App\Http\Resources\Attempt;

use App\Http\Resources\Exam\ExamResource;
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
            'examName' =>$this->whenLoaded('exam', fn () => $this->exam->examType->short_name),
            'answers' =>  AttemptAnswerResource::collection($this->whenLoaded('answers')),
            'expiredAt' => $this->expired_at,
            'solved' => $this->solved,
            'foreignNational' => new ForeignNationalResource($this->whenLoaded('foreignNational')),
            'id' => $this->id,
            'startedAt' => $this->started_at, //?->format('Y-m-d H:i:s')
            'finishedAt' => $this->finished_at?->format('Y-m-d H:i:s'),
            'isPassed' => $this->is_passed,
            'status' => $this->status
        ];
    }
}
