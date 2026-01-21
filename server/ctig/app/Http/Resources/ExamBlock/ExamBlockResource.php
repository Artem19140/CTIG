<?php

namespace App\Http\Resources\ExamBlock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Question\QuestionResource;

class ExamBlockResource extends JsonResource
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
            'name'=>$this->resource->name,
            'minMark'=>$this->resource->min_mark,
            'questions' => QuestionResource::collection($this->whenLoaded('questions'))
        ];
    }
}
