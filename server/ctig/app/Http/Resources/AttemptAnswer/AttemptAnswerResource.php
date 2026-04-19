<?php

namespace App\Http\Resources\AttemptAnswer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttemptAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'answer' => $this->answer,
            'id' => $this->id,
            'checkedAt' => $this->checked_at,
            'mark' => $this->mark,
            'audioPlayed' => $this->audio_played
        ];
    }
}
