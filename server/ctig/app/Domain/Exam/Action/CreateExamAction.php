<?php

namespace App\Domain\Exam\Action;


use App\Domain\Exam\Validator\ExamBeforeSaveValidator;
use App\Domain\Exam\Validator\ExaminersValidator;
use App\Http\Dto\ExamDto;
use App\Models\Exam;
use App\Models\Employee;
use DB;


final class CreateExamAction{
    public function __construct(
        protected ExamBeforeSaveValidator $examBeforeSaveValidator,
        protected ExaminersValidator $validateExaminers
    ){}
    public function execute(
        ExamDto $examDto, 
        Employee $employee
    ):Exam
    {
        $duration = $this->examBeforeSaveValidator->execute($examDto);
        
        return DB::transaction(function () use ($examDto, $employee, $duration) {
            $exam = Exam::create($this->getAttributes($examDto, $employee, $duration));
            $exam->examiners()->attach($examDto->examiners);
            return $exam;
        });        
    }

    protected function getAttributes(
        ExamDto $examDto, 
        Employee $employee, 
        int $duration
    ):array{
        return [
            'begin_time' => $examDto->beginTime,
            'address_id' => $examDto->addressId,
            'capacity' => $examDto->capacity,
            'exam_type_id' => $examDto->examTypeId,
            'comment' => $examDto->comment,
            'creator_id'=> $employee->id,
            'end_time' => $examDto->beginTime->copy()->addMinutes($duration),
            'center_id' => $employee->center_id
        ];
    }
}