<?php

namespace App\Actions\Exam;

use App\Exceptions\BusinessException;
use App\Models\Exam;
class CancelExamAction{
    public function execute(Exam $exam){
        if($exam->isCancelled()){
            throw new BusinessException('Экзамен уже отменен');
        }
        if($exam->isCompleted() || $exam->isGoing()){
            throw new BusinessException('Экзамен уже прошел или идет');
        }

        //Всем гражданам поставить запись - отменено
        
        $exam->cancelled_reason = request()->input('cancelledReason');
        $exam->is_cancelled = true;
        $exam->save();
    }
}