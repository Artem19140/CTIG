<?php

namespace App\Actions\StudentAnswer;

use App\Enums\TaskType;
use App\Exceptions\BusinessException;
use App\Models\StudentAnswer;
use App\Services\AnswerCheckService;


class HandleStudentAnswerAction{
    public function __construct(protected AnswerCheckService $answerCheckService){}

    public function execute(StudentAnswer $studentAnswer, array $answers){
        $studentAnswer->load(['taskVariant.task', 'taskVariant.answers']);
        $task = $studentAnswer->taskVariant->task;

        if(!$task->type->hasAnswers()){
            throw new BusinessException('Данному типу задания нельзя загрузить ответ');
        }

        $this->saveByType($task->type, $studentAnswer, $answers);
        if($task->type->autoCheck()){
            $rightAnswers = $this->getRightAnswers($task->type, $studentAnswer->taskVariant); //->pluck("content")
            $isCorrect = $this->answerCheckService->check( $answers,$rightAnswers, $task->type,);
            if($isCorrect){
                $studentAnswer->mark = $task->mark;
            }else{
                $studentAnswer->mark = 0;
            }  
            $studentAnswer->is_checked = true;
        }
        $studentAnswer->save();
    }

    public function getRightAnswers($taskType, $taskVariant){
        return match ($taskType) {
            TaskType::SingleChoice => $taskVariant->answers->where('is_correct')->pluck('id')->toArray(),
            TaskType::TextInput => $taskVariant->answers->where('is_correct')->pluck('content')->toArray(),
            default => throw new BusinessException("Неизвестный тип задания"),
        };
    }

    protected function saveByType($taskType, $studentAnswer, $answers){
        match ($taskType) {
            TaskType::SingleChoice => $this->saveSingleChoice($studentAnswer, $answers),
            TaskType::TextInput => $this->saveTextInput($studentAnswer, $answers),
            default => throw new BusinessException("Неизвестный тип задания"),
        };
    }

    protected function saveSingleChoice(StudentAnswer $studentAnswer, array $answers){
        if (count($answers) !== 1) {
            throw new BusinessException('Задание предполагает один вариант ответа');
        }
        $answerId = reset($answers);

        $validIds = $studentAnswer->taskVariant->answers->pluck('id')->toArray();
        if (!\in_array($answerId, $validIds, true)) {
            throw new BusinessException('Выбран недопустимый вариант ответа');
        }
        $studentAnswer->student_answer_id = $answerId;
    }

    protected function saveTextInput(StudentAnswer $studentAnswer,array $answers){
        if (count($answers) !== 1) {
            throw new BusinessException('Задание предполагает один вариант ответа');
        }
        $studentAnswer->student_answer = reset($answers);
    }
}