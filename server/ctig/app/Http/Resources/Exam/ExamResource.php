<?php

namespace App\Http\Resources\Exam;

use App\Domain\Exam\Resolver\ExamStatusResolver;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\Enrollment\EnrollmentResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ForeignNational\ForeignNationalResource;

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
            'foreignNationals' => ForeignNationalResource::collection($this->whenLoaded('foreignNationals')),//здесь если есть результаты, то и их можно взять
            'enrollments' => EnrollmentResource::collection($this->whenLoaded('enrollments')),
            'sessionNumber' => $this->session,
            'capacity' => $this->capacity,
            'comment'=>$this->comment,
            'protocolComment'=>$this->protocol_comment,
            'group' => $this->group,
            'examiners' => UserResource::collection($this->whenLoaded('examiners')),
            'name' => $this->whenLoaded('type', fn () => $this->type->name),
            'shortName' => $this->whenLoaded('type', fn () => $this->type->short_name),
            'examTypeId' => $this->whenLoaded('type', fn () => $this->type->id),
            'address' => $this->whenLoaded('address', fn () =>$this->address->address),
            'addressId' => $this->whenLoaded('address', fn () =>$this->address->id),
            'creator'=> new UserResource($this->whenLoaded('creator')),
            'createdAt' => $this->created_at,
            'foreignNationalsCount' => $this->whenCounted('foreignNationals_count'),
            'enrollmentsCount' => $this->whenCounted('enrollments_count'),
            'attempts' => AttemptResource::collection( $this->whenLoaded('attempts')),
            'tasksCount' => $this->whenLoaded('type', fn () => $this->type->tasks_count),
            'hasSpeakingTasks' => $this->whenLoaded('type', fn () => $this->type->has_speaking_tasks),
            'status' => app(ExamStatusResolver::class)->execute($this->resource),
            'codesAvailable' => $this->canGenerateCodes()
        ];
    }
}
