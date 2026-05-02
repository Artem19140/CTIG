<?php

namespace App\Http\Resources\Exam;

use App\Domain\Exam\Resolver\ExamStatusResolver;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Enrollment\EnrollmentResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
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
            'cancelledReason' => $this->when($this->isCancelled(), $this->cancelled_reason),
            'beginTime' =>$this->begin_time_local->copy()->toIso8601String(),
            'endTime' => $this->begin_time_local->copy()->addMinutes($this->duration)->toIso8601String(),
            'enrollments' => EnrollmentResource::collection($this->whenLoaded('enrollments')),
            'sessionNumber' => $this->session,
            'capacity' => $this->capacity,
            'comment'=>$this->comment,
            'group' => $this->group,
            'examiners' => UserResource::collection($this->whenLoaded('examiners')),
            'name' => $this->whenLoaded('type', fn () => $this->type->name),
            'shortName' => $this->whenLoaded('type', fn () => $this->type->short_name),
            'examTypeId' => $this->whenLoaded('type', fn () => $this->type->id),
            'address' => $this->whenLoaded('address', fn () =>$this->address->address),
            'addressId' => $this->whenLoaded('address', fn () =>$this->address->id),
            'creator'=> new UserResource($this->whenLoaded('creator')),
            'createdAt' => $this->created_at,
            'enrollmentsCount' => $this->whenCounted('enrollments_count'),
            'attempts' => AttemptResource::collection( $this->whenLoaded('attempts')),
            'tasksCount' => $this->whenLoaded('type', fn () => $this->type->tasks_count),
            'status' => app(ExamStatusResolver::class)->execute($this->resource),
            'codesAvailable' => $this->canGenerateCodes()
        ];
    }
}
