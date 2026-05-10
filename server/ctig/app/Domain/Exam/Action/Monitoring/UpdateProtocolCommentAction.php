<?php

namespace App\Domain\Exam\Action\Monitoring;

use App\Domain\Exam\Guard\ExamGuard;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\User;
use App\Support\Log\BusinessLog;

class UpdateProtocolCommentAction{
    public function __construct(
        protected ExamGuard $examGuard
    ){}
    public function execute(
        Exam $exam, 
        string $protocolComment,
        User $author
    ){
        $this->examGuard->ensureNotCancelled($exam);
        $this->ensureCanUpdateProtocolComment($exam);

        $exam->protocol_comment = $protocolComment;
        $exam->save();
        $this->log($author, $exam);
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

    protected function log(User $author, Exam $exam){
        BusinessLog::event('exam_protocol_comment_updated', [
            'user_id' => $author->id,
            'exam_id' => $exam->id
        ]);
    }
}