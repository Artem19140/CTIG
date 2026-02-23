<?php


namespace App\Services;

use App\Enums\TaskType;


class StudentAnswerCheckService{
    public function check($taskType, $studentAnswer, $rigthAnswer): bool{
        return match($taskType){
            TaskType::SingleChoice, TaskType::TextInput=> $studentAnswer === $rigthAnswer
        };
    }

    
}