<?php

namespace App\Domain\Exam\Action;

use App\Actions\Exam\Manage\ValidateExaminersAction;
use App\Models\Exam;
use App\Models\User;
use App\Validation\ExamValidation;

class UpdateExaminersAction{
    public function __construct(
        protected ExamValidation $examValidation,
        protected ValidateExaminersAction $validateExaminers
    ){}

    public function execute(array $examiners, Exam $exam, User $user){
        $this->examValidation->ensureNotCancelled($exam);
        $this->examValidation->ensureNotGoing($exam);
        $this->examValidation->ensureNotCompleted($exam);
        $this->validateExaminers->execute($examiners, $exam->begin_time, $exam->end_time, $exam->id);
        $exam->examiners()->syncWithPivotValues($examiners, ['center_id'  => $user->center->id]);
        $exam->save();
    }
}