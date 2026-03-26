<?php

namespace App\Actions\AttemptAnswer;

use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\TaskVariant;


class HandleAttemptAnswerAction{

    public function execute(mixed $answer, Attempt $attempt, int $taskVariantId){
        $taskVariant = TaskVariant::with(['answers', 'task'])->find($taskVariantId);
        if(!$taskVariant){
            throw new EntityNotFoundExсeption('Задание');
        }   

        $attemptAnswer = AttemptAnswer::
                                    where('task_variant_id', $taskVariant->id)
                                    ->where('attempt_id', $attempt->id)
                                    ->first();
        if(!$attemptAnswer){
            throw new BusinessException('Такого задания нет в экзаменационном варианте');
        }
        $task = $taskVariant->task;

        if(!$task->type->hasAnswers()){
            throw new BusinessException('Заданию нельзя загрузить ответ');
        }
        if($attemptAnswer->answer === null && $attemptAnswer->answer_id === null){
            $attempt->solved += 1;
        }
        if($task->type->autoCheck()){
            $isCorrect = $this->autoChecking($answer, $taskVariant, $task->type);
            $attemptAnswer->mark = $isCorrect ? $task->mark : 0;
            $attemptAnswer->is_checked = true;

            if($task->type === TaskType::SingleChoice){
                $attemptAnswer->answer_id = $answer;
                $answer= $taskVariant->answers->firstWhere('id', $answer);
                $attemptAnswer->answer= [
                    'id' => $answer->id,
                    'order' =>$answer->order
                ];
            }else{
                $attemptAnswer->answer= $answer;
            }
        }else{
            $attemptAnswer->answer = $answer;
        }        
        $attemptAnswer->save();
    }

    protected function autoChecking($answer, $taskVariant, $type){
        return  match($type) {
            TaskType::SingleChoice => $this->singleChoiceChecking($answer, $taskVariant),
            TaskType::TextInput => $this->textInputChecking($answer, $taskVariant),
            default => throw new BusinessException('Такой тип задания не существует')
        };
    }

    protected function singleChoiceChecking(int $answerId,TaskVariant $taskVariant){
        $answers = $taskVariant->answers;
        $answer = $answers->firstWhere('id', $answerId);
        if(!$answer){
            throw new BusinessException('Такого ответа у задания не существует');
        }
        return $answer->is_correct;
    }

    protected function textInputChecking(string $answer,TaskVariant $taskVariant){
        $answers = $taskVariant->answers->pluck('content');
        $answersToCompare = $answers->map(function ($item) {
            return mb_strtolower(trim($item), 'UTF-8');
        });
        
        $answerToCompare = mb_strtolower(trim($answer), 'UTF-8');
        return \in_array($answerToCompare, $answersToCompare->toArray());
    }
}