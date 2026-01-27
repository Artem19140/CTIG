<?php

namespace App\Http\Resources\ExamCode;

use App\Http\Resources\Student\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Student;

class ExamCodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'student' => new StudentResource($this->student)
        ];
    }
}
