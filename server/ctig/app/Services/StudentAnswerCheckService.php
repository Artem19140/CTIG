<?php


namespace App\Services;

use App\Enums\TaskTypeEnum;


class StudentAnswerCheckService{
    public function check($taskType, $studentAnswer, $rigthAnswer): bool{
        return match($taskType){
            TaskTypeEnum::SingleChoice, TaskTypeEnum::TextInput=> $studentAnswer === $rigthAnswer
        };
    }

    
}