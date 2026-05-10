<?php

namespace App\Domain\AttemptAnswer\Handlers;

use App\Enums\TaskType;
use App\Models\Task;
use App\Models\TaskVariant;

class TextInputTaskHandler{
    public function for(Task $task):bool{
        return $task->type === TaskType::TextInput;
    }

    public function validate(string $answer):string{
        return $answer;
    }

    public function calculateMark(string $answer, TaskVariant $taskVariant){
        $answers = $taskVariant->answers->pluck('content');
        
        $answersToCompare = $answers->map(function ($item) {
            return mb_strtolower(trim($item), 'UTF-8');
        });
        
        $answerToCompare = mb_strtolower(trim($answer), 'UTF-8');
        return \in_array($answerToCompare, $answersToCompare->toArray());
    }
}