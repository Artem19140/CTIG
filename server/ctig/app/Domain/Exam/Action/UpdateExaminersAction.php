<?php

namespace App\Domain\Exam\Action;

use App\Domain\Exam\Guard\ExamGuard;
use App\Domain\Exam\Rules\ValidateExaminers;
use App\Models\Exam;
use App\Models\User;


class UpdateExaminersAction{
    public function __construct(
        protected ExamGuard $examGuard,
        protected ValidateExaminers $validateExaminers
    ){}

    public function execute(array $examiners, Exam $exam, User $user){
        $this->examGuard->ensureNotCancelled($exam);
        $this->examGuard->ensureNotGoing($exam);
        $this->examGuard->ensureNotFinished($exam);
        $this->validateExaminers->execute($examiners, $exam->begin_time, $exam->end_time, $exam->id);
        
        $exam->examiners()->syncWithPivotValues($examiners, ['center_id'  => $user->center->id]);
        $exam->save();
    }
}