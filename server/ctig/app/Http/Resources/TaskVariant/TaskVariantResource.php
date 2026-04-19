<?php

namespace App\Http\Resources\TaskVariant;

use App\Http\Resources\Answer\AnswerResource;
use App\Http\Resources\AttemptAnswer\AttemptAnswerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskVariantResource extends JsonResource
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
            'content' => $this->content,
            'groupId' => $this->group_number,
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
            'order' => $this->whenLoaded('task', fn () => $this->task->order),
            'type' => $this->whenLoaded('task', fn () => $this->task->type),
            'mark' => $this->whenLoaded('task', fn () => $this->task->mark),
            'attemptAnswer' =>  $this->whenLoaded('attemptsAnswer', fn () => new AttemptAnswerResource($this->attemptsAnswer)),
            'description' => $this->whenLoaded('task', fn () => $this->task->description),
            
        ];
    }
}
