<?php

namespace App\Domain\AttemptAnswer\Handlers;

use App\Enums\TaskType;
use App\Exceptions\Attempt\AttemptAnswerValidationException;
use App\Models\Answer;
use App\Models\AttemptAnswer;
use App\Models\TaskVariant;

class MultyInputTaskHandler{
    public function for($task){
        return TaskType::MultyInput === $task->type;
    }

    public function validate(mixed $answer, AttemptAnswer $attemptAnswer){
        // $answer = $attemptAnswer->taskVariant->answer;
        // if(!$answer){
        //     throw new AttemptAnswerValidationException([
            
        //         'type' => TaskType::MultyInput->value,
        //         'message' => 'answer not exists on task variant'
        //     ]);
        // }

        return $answer;
    }
}