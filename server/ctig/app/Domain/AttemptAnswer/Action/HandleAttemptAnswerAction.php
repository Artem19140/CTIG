<?php

namespace App\Domain\AttemptAnswer\Action;

use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\TaskVariant;
use App\Domain\Attempt\Guard\AttemptGuard;


class HandleAttemptAnswerAction{
    public function __construct(
        protected AttemptGuard $attemptGuard
    ){}

    public function execute(mixed $answer, Attempt $attempt, AttemptAnswer $attemptAnswer):AttemptAnswer{
        $this->attemptGuard->ensureStarted($attempt);
        $this->attemptGuard->ensureNotBanned($attempt);
        $this->attemptGuard->ensureNotFinished($attempt);
        $this->attemptGuard->ensureNotExpired($attempt); 
        
        $attemptAnswer->load('taskVariant.task');
        $taskVariant = $attemptAnswer->taskVariant;
        $task = $taskVariant->task;

        if(!$task->type->hasAnswers()){
            throw new BusinessException('Заданию нельзя загрузить ответ');
        }

        if($task->type->autoCheck()){
            $isCorrect = $this->autoChecking($answer, $taskVariant, $task->type);
            $attemptAnswer->mark = $isCorrect ? $task->mark : 0;

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
        return $attemptAnswer;
    }

    protected function autoChecking($answer, $taskVariant, $type){
        return match($type) {
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