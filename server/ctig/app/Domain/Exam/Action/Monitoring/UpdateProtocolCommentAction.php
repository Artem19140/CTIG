<?php

namespace App\Domain\Exam\Action\Monitoring;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use Illuminate\Support\Facades\Log;

class UpdateProtocolCommentAction{
    public function __construct(
        protected ExamGuard $examGuard
    ){}
    public function execute(
        Exam $exam, 
        string $protocolComment
    ){
        $this->examGuard->ensureNotCancelled($exam);
        $this->ensureCanUpdateProtocolComment($exam);
        $oldValue = $exam->protocol_comment;
        $exam->protocol_comment = $protocolComment;
        $exam->save();
        $this->log($exam, $oldValue);
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

    protected function log(Exam $exam, string $oldValue){
        Log::info('updated_protocol_comment', [
            'exam_id' => $exam->id,
            'field' => 'protocol_comment',
            'before' => $oldValue,
            'after' => $exam->protocol_comment,
        ]);
    }
}