<?php

namespace App\Domain\Exam\Action\Monitoring;

use App\Domain\Exam\Guard\ExamGuard;
use App\Enums\Event;
use App\Enums\Resource;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Support\Log\LogActivity;

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
        LogActivity::event(
            event:Event::Updated,
            resource:Resource::Exam,
            context:[
                'exam_id' => $exam->id,
                'field' => 'protocol_comment',
                'before' => $oldValue,
                'after' => $exam->protocol_comment,
            ]
        );
    }
}