<?php

namespace App\Domain\Exam\Action\Monitoring;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Exam;

class UpdateProtocolCommentAction{
    public function __construct(
        protected ExamGuard $examGuard
    ){}
    public function execute(Exam $exam, string $protocolComment){
        $this->examGuard->ensureNotCancelled($exam);
        $this->ensureCanUpdateProtocolComment($exam);

        $exam->protocol_comment = $protocolComment;
        $exam->save();
    }

    protected function ensureCanUpdateProtocolComment(Exam $exam){
        if(
            !$exam->begin_time->isToday() 
                ||
            !$exam->begin_time->isPast()
        ){
            throw new BusinessException('Комментарий возможно редактировать во время и в течении всего дня после экзамена');
        }
    }
}