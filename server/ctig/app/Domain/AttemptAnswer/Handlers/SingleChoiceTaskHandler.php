<?php

namespace App\Domain\AttemptAnswer\Handlers;

use App\Enums\TaskType;
use App\Exceptions\Attempt\AttemptAnswerValidationException;
use App\Models\Answer;
use App\Models\AttemptAnswer;
use App\Models\TaskVariant;

class SingleChoiceTaskHandler
{
    public function for($task):bool
    {
        return TaskType::SingleChoice === $task->type;
    }

    public function validate(
        mixed $answerId, 
        AttemptAnswer $attemptAnswer
    ):Answer
    {
        if(!\is_int($answerId)){
            throw new AttemptAnswerValidationException([
                'attempt_answer_id' => $attemptAnswer->id,
                'type' => TaskType::SingleChoice->value,
                'message' => 'not_valid_format'
            ]);
        }
        
        $answers = $attemptAnswer->taskVariant->answers;
                
        $answer = $answers->firstWhere('id', $answerId);

        if(!$answer){
            throw new AttemptAnswerValidationException([
                'attempt_answer' => $answerId,
                'type' => TaskType::SingleChoice->value,
                'message' => 'answer not exists on task variant'
            ]);
        }

        return $answer;
    }

    public function calculateMark(
        Answer $answer,
        TaskVariant $taskVariant
    ):int
    {  
        return $answer->is_correct ? $taskVariant->task->mark : 0;
    }
}