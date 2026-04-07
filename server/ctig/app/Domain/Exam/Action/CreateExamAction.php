<?php

namespace App\Domain\Exam\Action;


use App\Http\Dto\ExamDto;
use App\Models\Exam;
use App\Models\User;
use DB;
use App\Actions\Exam\Manage\ValidateExamCreationAction;

final class CreateExamAction{
    public function __construct(
        protected ValidateExamCreationAction $validateExamCreation
    ){}
    public function execute(ExamDto $examDto, User $user){
        $duration = $this->validateExamCreation->execute($examDto, $user);
        $exam = DB::transaction(function () use ($examDto, $user, $duration) {
            $exam = Exam::create([
                    'begin_time' => $examDto->beginTime,
                    'begin_time_utc' => $examDto->beginTime->copy()->utc(),
                    'address_id' => $examDto->addressId,
                    'capacity' => $examDto->capacity,
                    'exam_type_id' => $examDto->examTypeId,
                    'comment' => $examDto->comment,
                    'creator_id'=> $user->id,
                    'end_time' => $examDto->beginTime->copy()->addMinutes($duration),
                    'center_id' => $user->center->id
            ]);
        
            $exam->examiners()->attach($examDto->examiners, ['center_id'  => $user->center->id]);
            return $exam;
        });        
        return $exam;
    }
}