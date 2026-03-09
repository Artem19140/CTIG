<?php

namespace App\Actions\Exam;

use App\Exceptions\BusinessException;
use App\Models\Exam;
class CancelExamAction{
    public function execute(Exam $exam){
        if($exam->isPassed() || $exam->isGoing()){
            throw new BusinessException('Экзамен уже прошел или идет');
        }

        if($exam->is_cancelled){
            throw new BusinessException('Экзамен уже отменен');
        }

        //Всем студентам поставить запись - отменено
        
        $exam->cancelled_reason = request()->input('cancelledReason');
        $exam->is_cancelled = true;
        $exam->save();
    }
}