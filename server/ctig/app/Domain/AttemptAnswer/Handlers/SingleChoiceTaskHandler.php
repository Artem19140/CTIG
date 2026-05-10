<?php

namespace App\Domain\AttemptAnswer\Handlers;

use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Models\Answer;
use App\Models\TaskVariant;

class SingleChoiceTaskHandler{
    public function for($task){
        return TaskType::SingleChoice === $task->type;
    }

    public function validate(int $answerId, TaskVariant $taskVariant){
        $answers = $taskVariant->answers;
        $answer = $answers->firstWhere('id', $answerId);
        if(!$answer){
            throw new BusinessException('Такого ответа у задания не существует');
        }
        return $answer;
    }

    public function calculateMark(Answer $answer,TaskVariant $taskVariant){  
        return $answer->is_correct ? $taskVariant->task->mark : 0;
    }
}