<?php

namespace App\Actions\Attempt;

use App\Enums\TaskType;

class AutoCheckAnswerAction{
    public function execute(array $answers, array $rightAnswers,  $taskType){
        
        $result = match($taskType){
            TaskType::SingleChoice =>  $this->singleChoice($answers, $rightAnswers),
            TaskType::TextInput => $this->textInput($answers, $rightAnswers)
        };
        return $result;
    }

    protected function singleChoice($answers, $rightAnswers){
        sort($rightAnswers);
        sort($answers);
        return $rightAnswers === $answers;
    }

    protected function textInput($answers, $rightAnswers){
        $normalize = fn($val) => strtolower(trim((string)$val));
        $answers = array_map($normalize, $answers);
        $rightAnswers = array_map($normalize, $rightAnswers);
        return !empty(array_intersect($answers, $rightAnswers)) && count($answers)=== 1;
    }
}