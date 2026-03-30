<?php

namespace App\Actions\Exam\Monitoring;

use App\Actions\Exam\Validation\EnsureExamIsNotCancelledAction;
use App\Exceptions\BusinessException;
use App\Models\Exam;

class UpdateProtocolCommentAction{
    public function __construct(
        protected EnsureExamIsNotCancelledAction $ensureExamIsNotCancelled
    ){}
    public function execute(Exam $exam, string $protocolComment){
        $this->ensureExamIsNotCancelled->execute($exam);
        if(
            $exam->begin_time_utc->addMinutes(30)->isPast() 
                &&
            !$exam->begin_time_utc->isPast()
        ){
            throw new BusinessException('Комментарий можно редактировать во время и в течении 30 минут после экзамена');
        }

        $exam->protocol_comment = $protocolComment;
        $exam->save();
    }
}