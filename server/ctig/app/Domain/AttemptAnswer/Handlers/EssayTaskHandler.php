<?php

namespace App\Domain\AttemptAnswer\Handlers;

use App\Enums\TaskType;
use App\Exceptions\Attempt\AttemptAnswerValidationException;
use App\Models\AttemptAnswer;
use App\Models\Task;

class EssayTaskHandler{
    public function for(Task $task):bool
    {
        return $task->type === TaskType::Essay;
    }

    public function validate(
        mixed $foreignNationalAnswer, 
        AttemptAnswer $attemptAnswer
    ): string
    {
        if(!\is_string($foreignNationalAnswer)){
            throw new AttemptAnswerValidationException([
                'attempt_answer_id' => $attemptAnswer->id,
                'type' => TaskType::Essay,
                'message' => 'not_valid_format'
            ]);
        }
        return $foreignNationalAnswer;
    }
}