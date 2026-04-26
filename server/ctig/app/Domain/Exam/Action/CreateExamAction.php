<?php

namespace App\Domain\Exam\Action;


use App\Domain\Exam\Rules\ValidateExamForSave;
use App\Http\Dto\ExamDto;
use App\Models\Exam;
use App\Models\User;
use DB;


final class CreateExamAction{
    public function __construct(
        protected ValidateExamForSave $validateExamForSave
    ){}
    public function execute(ExamDto $examDto, User $user){
        $duration = $this->validateExamForSave->execute($examDto, $user);
        
        return DB::transaction(function () use ($examDto, $user, $duration) {
            $exam = Exam::create($this->getAttributes($examDto, $user, $duration));
            $exam->examiners()->attach($examDto->examiners, ['center_id'  => $user->center->id]);
            return $exam;
        });        
    }

    protected function getAttributes(ExamDto $examDto, User $user, int $duration):array{
        return [
            'begin_time' => $examDto->beginTime,
            'begin_time_utc' => $examDto->beginTime->copy()->utc(),
            'address_id' => $examDto->addressId,
            'capacity' => $examDto->capacity,
            'exam_type_id' => $examDto->examTypeId,
            'comment' => $examDto->comment,
            'creator_id'=> $user->id,
            'end_time' => $examDto->beginTime->copy()->addMinutes($duration),
            'center_id' => $user->center->id
        ];
    }
}