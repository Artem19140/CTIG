<?php

namespace App\Domain\AttemptAnswer\Handlers;

use App\Enums\TaskType;
use App\Exceptions\Attempt\AttemptAnswerValidationException;
use App\Models\AttemptAnswer;

class MultyInputTaskHandler
{
    public function for($task):bool
    {
        return TaskType::MultyInput === $task->type;
    }

    public function validate(
        mixed $foreignNationalAnswer,
        AttemptAnswer $attemptAnswer
    ):array
    {    
        $etalonAnswers = $attemptAnswer->taskVariant->answers[0]->content;

        $normalizedEtalonKeys = $this->getAndNormalizeKeys($etalonAnswers);
        $normalizedForeignKeys = $this->getAndNormalizeKeys($foreignNationalAnswer);

        $this->ensureMatchingKeys(
            $normalizedForeignKeys, 
            $normalizedEtalonKeys
        );

        return $foreignNationalAnswer;
    }

    protected function getAndNormalizeKeys(array $answers):array
    {
        $keys = array_keys($answers);
        sort($keys);
        return $keys;
    }

    protected function ensureMatchingKeys(
        array $normalizedForeignKeys,
        array $normalizedEtalonKeys
    ):void
    {
        if($normalizedEtalonKeys !== $normalizedForeignKeys){
            throw new AttemptAnswerValidationException([
                'type' => TaskType::MultyInput->value,
                'message' => 'errorValidation'
            ]);
        }
    }
}