<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Exam\Guard\ExamGuard;
use App\Enums\Event;
use App\Enums\Resource;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Support\Log\LogActivity;


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
        $this->log($enrollment);
    }

    protected function log(Enrollment $enrollment){
        LogActivity::event(
            event: Event::Updated,
            resource: Resource::Enrollment,
            context:[
                'payment_status'=> $enrollment->hasPayment(),
                'enrollment_id'=> $enrollment->id
            ]
        );
    }
}