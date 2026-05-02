<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;


class ChangePaymentStatusAction{
    public function __construct(
        protected ExamGuard $examGuard
    ){}

    public function execute(Enrollment $enrollment){
        $enrollment->load('exam');
        
        if($enrollment->attempt()->exists()){
            throw new BusinessException('Нельзя изменить статус оплаты, если есть попытка экзамена');
        }
    
        $this->examGuard->ensureNotCancelled($enrollment->exam);
        $this->examGuard->ensureNotFinished($enrollment->exam);

        $enrollment->changePaymentStatus();
        $enrollment->save();
    }
}