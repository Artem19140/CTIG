<?php

namespace App\Actions\AttemptAnswer;

use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Models\AttemptAnswer;


class HandleAttemptAnswerAction{

    public function execute(AttemptAnswer $attemptAnswer, $answer){
        $attemptAnswer->load(['taskVariant.task', 'taskVariant.answers']);
        $task = $attemptAnswer->taskVariant->task;

        if(!$task->type->hasAnswers()){
            throw new BusinessException('Данному типу задания нельзя загрузить ответ');
        }

        if($task->type->autoCheck()){
            $isCorrect = $this->autoChecker($task->type, $answer, $attemptAnswer);

            if($isCorrect){
                $attemptAnswer->mark = $task->mark;
            }else{
                $attemptAnswer->mark = 0;
            }
            $attemptAnswer->is_checked = true;
        }else{
            $attemptAnswer->answer = $answer;
        }
        $attemptAnswer->save();
    }

    protected function autoChecker(TaskType $taskType, int|string $answer, AttemptAnswer $attemptAnswer): bool{
        return match ($taskType){
            TaskType::SingleChoice => $this->singleChoiceChecker($answer,  $attemptAnswer),
            TaskType::TextInput => $this->textTypeChecker($answer,  $attemptAnswer),
            default => throw new BusinessException('Нет такого типа задания')
        };

    }

    protected function singleChoiceChecker(int $answerId,AttemptAnswer $attemptAnswer): bool{

        $answer = $attemptAnswer->taskVariant->answers->firstWhere('id', $answerId);
        // echo $answer;
        //echo $attemptAnswers->taskVariant->answers->pluck('id');
        if (!$answer) {
            throw new BusinessException('Не существует данного ответа');
        }
        $attemptAnswer->answer = [
            'value' => [
                "id" => $answer->id,
                'order' => $answer->order,
                'content' => $answer->content
            ]
            
        ];
        return $answer->is_correct;
    }

    public function textTypeChecker(string $answer,AttemptAnswer $attemptAnswer): bool{
        if(!\is_string($answer)){
            throw new BusinessException('Ответ должен быть строкой');
        }
        $rigthAnswers = $attemptAnswer->taskVariant->answers
                        ->pluck('content')
                        ->map(fn($item) => mb_strtolower(trim($item)))
                        ->toArray();
        $isCorrect = \in_array(mb_strtolower(trim($answer)), $rigthAnswers); 
        $attemptAnswer->is_correct = $isCorrect;
        $attemptAnswer->answer = [
                'value' => $answer
            ];
        return $isCorrect;
    }
}