<?php

namespace App\Actions\Exam;

use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;

class CheckExamRelevanceAction{
    public function execute(Exam|int $exam){
        if($exam instanceof Exam){
            $exam;
        }else{
            $exam = Exam::find($exam);
            if(!$exam){
                throw new EntityNotFoundExсeption('Экзамен');
            }
        }

        if($exam->isCancelled()){
            throw new BusinessException('Экзамен отменен');
        }

        if($exam->isCompleted()){
            throw new BusinessException('Экзмен уже прошел');
        }
        return $exam;
    }
}