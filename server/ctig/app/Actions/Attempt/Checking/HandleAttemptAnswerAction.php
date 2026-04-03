<?php

namespace App\Actions\Attempt\Checking;

use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\TaskVariant;
use App\Validation\AttemptValidation;


class HandleAttemptAnswerAction{
    public function __construct(
        protected AttemptValidation $attemptValidation
    ){}

    public function execute(mixed $answer, Attempt $attempt, AttemptAnswer $attemptAnswer):AttemptAnswer{
        $this->attemptValidation->ensureActive($attempt);
        $this->attemptValidation->ensureNotBanned($attempt);
        if($attempt->exam_id !== $attemptAnswer->exam_id){
            abort(404);
        }
        //$taskVariant = TaskVariant::with(['answers', 'task'])->find($taskVariantId); 
        $attemptAnswer->load('taskVariant.task');
        if(!$attemptAnswer){
            throw new BusinessException('Такого задания нет в экзаменационном варианте');
        }
        $taskVariant = $attemptAnswer->taskVariant;
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