<?php

namespace App\Actions\StudentAnswer;

use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Models\Answer;
use App\Models\StudentAnswer;
use App\Services\AnswerCheckService;


class HandleStudentAnswerAction{

    public function execute(StudentAnswer $studentAnswer, $answer){
        $studentAnswer->load(['taskVariant.task', 'taskVariant.answers']);
        $task = $studentAnswer->taskVariant->task;

        if(!$task->type->hasAnswers()){
            throw new BusinessException('Данному типу задания нельзя загрузить ответ');
        }

        if($task->type->autoCheck()){
            $isCorrect = $this->autoChecker($task->type, $answer, $studentAnswer);
            if($isCorrect){
                $studentAnswer->mark = $task->mark;
            }else{
                $studentAnswer->mark = 0;
            }

        }
        $studentAnswer->save();
    }

    protected function autoChecker(TaskType $taskType, int|string $answer, StudentAnswer $studentAnswer): bool{
        return match ($taskType){
            TaskType::SingleChoice => $this->singleChoiceChecker($answer,  $studentAnswer),
            TaskType::TextInput => $this->textTypeChecker($answer,  $studentAnswer),
            default => throw new BusinessException('Нет такого типа задания')
        };

    }

    protected function singleChoiceChecker(int $answerId,StudentAnswer $studentAnswer): bool{
        $answer = $studentAnswer->taskVariant->answers->firstWhere('id', $answerId);
        if (!$answer) {
            throw new BusinessException('Не существует данного ответа');
        }
        $studentAnswer->is_correct = $answer->is_correct;
        $studentAnswer->answer = [
            'value' => [
                "id" => $answer->id,
                'order' => $answer->order,
                'content' => $answer->content
            ]
            
        ];
        return $answer->is_correct;
    }

    public function textTypeChecker(string $answer,StudentAnswer $studentAnswer): bool{
        if(!\is_string($answer)){
            throw new BusinessException('Ответ должен быть строкой');
        }
        $rigthAnswers = $studentAnswer->taskVariant->answers
                        ->pluck('content')
                        ->map(fn($item) => mb_strtolower(trim($item)))
                        ->toArray();
        $isCorrect = \in_array(mb_strtolower(trim($answer)), $rigthAnswers); 
        $studentAnswer->is_correct = $isCorrect;
        $studentAnswer->answer = [
                'value' => $answer
            ];
        return $isCorrect;
    }
}