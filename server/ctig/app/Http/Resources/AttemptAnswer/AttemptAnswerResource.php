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
            'audioIsPlayed' => $this->audio_is_played,
            'att_id' => $this->attempt->id
        ];
    }
}
