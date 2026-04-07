<?php

namespace App\Domain\Exam\Action;

use App\Actions\Exam\Manage\ValidateExamCreationAction;
use App\Exceptions\BusinessException;
use App\Http\Dto\ExamDto;
use App\Models\Exam;

use App\Models\User;
use App\Validation\ExamValidation;
use DB;


final class UpdateExamAction{
    public function __construct(
        protected ExamValidation $examValidation,
        protected ValidateExamCreationAction $validateExamCreation
    ){}
    public function execute(Exam $exam, ExamDto $examDto, User $user){
        if($exam->foreignNationals()->count() > 0){
            throw new BusinessException('Нельзя редактировать экзамен, на который уже записаны ИГ');
        }
        $this->examValidation->ensureNotCancelled($exam);
        $this->examValidation->ensureNotGoing($exam);
        $this->examValidation->ensureNotCompleted($exam);
        $duration = $this->validateExamCreation->execute($examDto, $user, $exam->id);
        $exam = DB::transaction(function () use ($examDto, $user, $duration, $exam) {
            $exam->update([
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

            $exam->examiners()->syncWithPivotValues($examDto->examiners, ['center_id'  => $user->center->id]);
            $exam->save();
            return $exam;
        });        
    }
}