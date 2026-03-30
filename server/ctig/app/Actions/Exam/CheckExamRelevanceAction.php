<?php

namespace App\Actions\Exam;

use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Actions\Exam\Validation\EnsureExamIsNotCancelledAction;

class CheckExamRelevanceAction{
    
    public function __construct(
        protected EnsureExamIsNotCancelledAction $ensureExamIsNotCancelled
        
    ){}
    public function execute(Exam|int $exam){
        if($exam instanceof Exam){
            $exam;
        }else{
            $exam = Exam::find($exam);
            if(!$exam){
                throw new EntityNotFoundExсeption('Экзамен');
            }
        }

        $this->ensureExamIsNotCancelled->execute($exam);
        if($exam->isCompleted()){
            throw new BusinessException('Экзмен уже прошел');
        }
        return $exam;
    }
}