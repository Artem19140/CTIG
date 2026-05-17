<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Log;


class ChangePaymentStatusAction{
    public function __construct(
        protected ExamGuard $examGuard
    ){}

    public function execute(Enrollment $enrollment):void{

        $enrollment->load('exam');

        $this->examGuard->ensureNotCancelled($enrollment->exam);
        $this->examGuard->ensureNotFinished($enrollment->exam);

        if($enrollment->attempt()->exists()){
            throw new BusinessException('Нельзя изменить статус оплаты, если есть попытка экзамена');
        }

        $enrollment->changePaymentStatus();
        $enrollment->save();
        
        Log::info('enrollment_payment_change',[
            'payment_status'=> $enrollment->hasPayment(),
            'enrollment_id'=> $enrollment->id
        ]);
    }
}